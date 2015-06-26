<?php

/**
 * Operator Business Details Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Olcs\Controller\Operator;

use Common\Service\Entity\OrganisationEntityService;
use Common\Service\Entity\AddressEntityService;
use Dvsa\Olcs\Transfer\Command\Operator\Create as CreateDto;
use Dvsa\Olcs\Transfer\Command\Operator\Update as UpdateDto;
use Olcs\Data\Mapper\OperatorBusinessDetails as Mapper;
use Dvsa\Olcs\Transfer\Query\Operator\BusinessDetails as BusinessDetailsDto;

/**
 * Operator Business Details Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class OperatorBusinessDetailsController extends OperatorController
{
    /**
     * @var string
     */
    protected $section = 'business_details';

    /**
     * @var string
     */
    protected $subNavRoute = 'operator_profile';
    
    protected $organisation = null;

    /**
     * Index action
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $operator = $this->params()->fromRoute('organisation');
        $this->loadScripts(['operator-profile']);
        $post = $this->params()->fromPost();
        $validateAndSave = true;

        if ($this->isButtonPressed('cancel')) {
            // user pressed cancel button in edit form
            if ($operator) {
                $this->flashMessenger()->addSuccessMessage('Your changes have been discarded');
                return $this->redirectToRoute('operator/business-details', ['organisation' => $operator]);
            } else {
                // user pressed cancel button in add form
                return $this->redirectToRoute('operators/operators-params');
            }
        }

        if ($this->getRequest()->isPost()) {
            // if this is post always take organisation type from parameters
            $operatorType = $post['operator-business-type']['type'];
        } elseif (!$operator) {
            // we are in add mode, this is default organisation type
            $operatorType = OrganisationEntityService::ORG_TYPE_REGISTERED_COMPANY;
        } else {
            // we are in edit mode, need to fetch original data
            $organisation = $this->getOrganisation($operator);
            $operatorType = $organisation['type']['id'];
        }

        $form = $this->makeFormAlterations($operatorType, $this->getForm('Operator'));
        // don't need validate form and save data if user just changed organisation's type
        if (isset($post['operator-business-type']['refresh'])) {
            // non-js version of form
            unset($post['operator-business-type']['refresh']);
            $validateAndSave = false;
        }

        /* if we are in edit mode and just changed the business type or
         * this is not a post we need to populate form with
         * original values, otherwise we use POST values
         */
        if ($operator && (!$validateAndSave || !$this->getRequest()->isPost())) {
            $originalData = Mapper::mapFromResult($this->getOrganisation($operator));
            if (!$validateAndSave) {
                $originalData['operator-business-type']['type'] = $operatorType;
            }
            $form->setData($originalData);            
        } else {
            $form->setData($post);
        }

        // process company lookup
        if (isset($post['operator-details']['companyNumber']['submit_lookup_company'])) {
            $this->getServiceLocator()->get('Helper\Form')
                ->processCompanyNumberLookupForm($form, $post, 'operator-details', 'registeredAddress');
            $validateAndSave = false;
        }

        if ($this->getRequest()->isPost() && $validateAndSave) {
            if (!$this->getEnabledCsrf()) {
                $this->getServiceLocator()->get('Helper\Form')->remove($form, 'csrf');
            }
            if ($form->isValid()) {

                $action = $operator ? 'edit' : 'add';
                $this->saveForm($form, $action);

                // we need to process redirect and catch flashMessenger messages if available
                if ($this->getResponse()->getStatusCode() == 302) {
                    return $this->getResponse();
                }
                // need to reload form, to update version and all other fields, because we are still on the same page
                $form = $this->setDataOperatorForm($form, $this->prepareOriginalData($operator));
            }
        }

        $view = $this->getView(['form' => $form]);
        $view->setTemplate('partials/form');
        return $this->renderView($view);
    }

    /**
     * Save form
     *
     * @param Zend\Form\Form $form
     * @param strring $action
     * @return mixed
     */
    private function saveForm($form, $action)
    {
        $data = $form->getData();
        
        $params = Mapper::mapFromForm($data);

        if ($action == 'edit') {
            $message = 'The operator has been updated successfully';
            $dto = UpdateDto::create($params);
        } else {
            $message = 'The operator has been created successfully';
            $dto = CreateDto::create($params);
        }

        $command = $this->getServiceLocator()->get('TransferAnnotationBuilder')->createCommand($dto);
        /** @var \Common\Service\Cqrs\Response $response */
        $response = $this->getServiceLocator()->get('CommandService')->send($command);
        if ($response->isOk()) {
            $this->flashMessenger()->addSuccessMessage($message);
            $orgId = $response->getResult()['id']['organisation'];
            return $this->redirectToRoute('operator/business-details', ['organisation' => $orgId]);
        }
        if ($response->isClientError()) {
            $this->mapErrors($form, $response->getResult()['messages']);
        }
        if ($response->isServerError()) {
            $this->addErrorMessage('unknown-error');
        }
    }

    protected function mapErrors($form, array $errors)
    {
        Mapper::mapFromErrors($form, $errors);
        if (!empty($errors)) {
            $fm = $this->getServiceLocator()->get('Helper\FlashMessenger');
            foreach ($errors as $error) {
                $fm->addCurrentErrorMessage($error);
            }
        }
    }

    /**
     * Make form alterations
     *
     * @param string $businessType
     * @param Zend\Form\Form $form
     * @return form
     */
    private function makeFormAlterations($businessType, $form)
    {
        $formHelper = $this->getServiceLocator()->get('Helper\Form');
        switch ($businessType) {
            case OrganisationEntityService::ORG_TYPE_REGISTERED_COMPANY:
            case OrganisationEntityService::ORG_TYPE_LLP:
                $formHelper->remove($form, 'operator-details->firstName');
                $formHelper->remove($form, 'operator-details->lastName');
                $formHelper->remove($form, 'operator-details->personId');
                break;
            case OrganisationEntityService::ORG_TYPE_SOLE_TRADER:
                $formHelper->remove($form, 'operator-details->companyNumber');
                $formHelper->remove($form, 'operator-details->name');
                $formHelper->remove($form, 'registeredAddress');
                break;
            case OrganisationEntityService::ORG_TYPE_PARTNERSHIP:
            case OrganisationEntityService::ORG_TYPE_OTHER:
                $formHelper->remove($form, 'operator-details->firstName');
                $formHelper->remove($form, 'operator-details->lastName');
                $formHelper->remove($form, 'operator-details->personId');
                $formHelper->remove($form, 'registeredAddress');
                $formHelper->remove($form, 'operator-details->companyNumber');
                break;
            case OrganisationEntityService::ORG_TYPE_IRFO:
                $formHelper->remove($form, 'operator-details->companyNumber');
                $formHelper->remove($form, 'operator-details->natureOfBusinesses');
                $formHelper->remove($form, 'operator-details->information');
                $formHelper->remove($form, 'operator-details->firstName');
                $formHelper->remove($form, 'operator-details->lastName');
                $formHelper->remove($form, 'operator-details->personId');
                $formHelper->remove($form, 'operator-details->isIrfo');
                $formHelper->remove($form, 'registeredAddress');
                break;
        }
        return $form;
    }

    private function getOrganisation($organisationId)
    {
        if (!$this->organisation) {
            $response = $this->handleQuery(BusinessDetailsDto::create(['id' => $organisationId]));

            if ($response->isClientError() || $response->isServerError()) {
                $this->getServiceLocator()->get('Helper\FlashMessenger')->addCurrentErrorMessage('unknown-error');
                return $this->notFoundAction();
            }            
            $this->organisation = $response->getResult();
        }
        return $this->organisation;
    }
}
