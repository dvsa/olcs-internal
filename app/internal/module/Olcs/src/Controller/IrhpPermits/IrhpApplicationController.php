<?php

/**
 * IRHP Application Controller
 *
 * @author Andy Newton <andy@vitri.ltd>
 */

namespace Olcs\Controller\IrhpPermits;

use Common\Controller\Interfaces\ToggleAwareInterface;
use Common\Data\Mapper\Permits\NoOfPermits;
use Common\FeatureToggle;
use Common\RefData;
use Common\Service\Cqrs\Exception\NotFoundException;
use Common\Service\Qa\UsageContext;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\CancelApplication;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\Grant;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\ReviveFromUnsuccessful;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\ReviveFromWithdrawn;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\SubmitApplication;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\Withdraw;
use Olcs\Form\Model\Form\IrhpApplicationWithdraw as WithdrawForm;
use Olcs\Form\Model\Form\IrhpCandidatePermit as IrhpCandidatePermitForm;
use Dvsa\Olcs\Transfer\Query\IrhpApplication\ApplicationPath;
use Dvsa\Olcs\Transfer\Query\IrhpPermitApplication\GetList as ListDTO;
use Dvsa\Olcs\Transfer\Query\IrhpCandidatePermit\GetListByIrhpApplication as CandidateListDTO;
use Dvsa\Olcs\Transfer\Query\IrhpPermitStock\AvailableCountries;
use Dvsa\Olcs\Transfer\Query\IrhpPermitWindow\OpenByCountry;
use Dvsa\Olcs\Transfer\Query\IrhpApplication\ById as ItemDto;
use Dvsa\Olcs\Transfer\Query\IrhpCandidatePermit\ById as CandidatePermitItemDTO;
use Dvsa\Olcs\Transfer\Query\IrhpApplication\MaxStockPermits;
use Dvsa\Olcs\Transfer\Query\IrhpPermitWindow\OpenByType;
use Dvsa\Olcs\Transfer\Query\IrhpPermitType\ById as PermitTypeQry;
use Dvsa\Olcs\Transfer\Query\Licence\Licence as LicenceDto;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\CreateFull as CreateDTO;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\UpdateFull as UpdateDTO;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\Create as QaCreateDTO;
use Dvsa\Olcs\Transfer\Command\IrhpCandidatePermit\Delete as CandidatePermitDeleteCmd;
use Dvsa\Olcs\Transfer\Command\IrhpCandidatePermit\Update as CandidatePermitUpdateCmd;
use Dvsa\Olcs\Transfer\Command\IrhpCandidatePermit\Create as CandidatePermitCreateCmd;
use Dvsa\Olcs\Transfer\Query\IrhpApplication\RangesByIrhpApplication as RangesDTO;
use Olcs\Controller\Interfaces\IrhpApplicationControllerInterface;
use Olcs\Mvc\Controller\ParameterProvider\AddFormDefaultData;
use Olcs\Form\Model\Form\IrhpApplication;
use Olcs\Controller\AbstractInternalController;
use Olcs\Controller\Interfaces\LeftViewProvider;
use Olcs\Data\Mapper\IrhpApplication as IrhpApplicationMapper;
use Olcs\Data\Mapper\IrhpCandidatePermit as IrhpCandidatePermitMapper;
use Olcs\Mvc\Controller\ParameterProvider\ConfirmItem;
use Olcs\Mvc\Controller\ParameterProvider\GenericItem;
use Olcs\Mvc\Controller\ParameterProvider\GenericList;
use Zend\Form\Form;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IrhpApplicationController extends AbstractInternalController implements
    IrhpApplicationControllerInterface,
    LeftViewProvider,
    ToggleAwareInterface
{
    use ShowIrhpApplicationNavigationTrait;

    protected $toggleConfig = [
        'default' => [
            FeatureToggle::BACKEND_PERMITS
        ],
    ];

    protected $routeIdentifier = 'irhp-application';

    protected $navigationId = 'licence_irhp_applications';

    // Maps the route parameter irhpPermitId to the "id" parameter in the the ById (ItemDTO) query.
    protected $itemParams = ['id' => 'irhpAppId'];

    // Maps the licence route parameter into the ListDTO as licence => value
    protected $listVars = ['licence'];
    protected $listDto = ListDto::class;
    protected $itemDto = ItemDto::class;
    protected $formClass = IrhpApplication::class;
    protected $addFormClass = IrhpApplication::class;
    protected $mapperClass = IrhpApplicationMapper::class;
    protected $createCommand = CreateDto::class;
    protected $updateCommand = UpdateDto::class;

    protected $addContentTitle = 'Add Irhp Application';

    const PERMIT_TYPE_LABELS = [
        RefData::IRHP_BILATERAL_PERMIT_TYPE_ID => 'Bilateral',
        RefData::IRHP_MULTILATERAL_PERMIT_TYPE_ID => 'Multilateral',
    ];

    // After Adding and Editing we want users taken back to index dashboard
    protected $redirectConfig = [
        'add' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'edit' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'cancel' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'submit' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'grant' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'withdraw' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'revivefromwithdrawn' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'revivefromunsuccessful' => [
            'route' => 'licence/permits',
            'action' => 'index',
        ],
        'pregrantedit' => [
            'route' => 'licence/irhp-application/application',
            'action' => 'preGrant',
        ],
        'pregrantadd' => [
            'route' => 'licence/irhp-application/application',
            'action' => 'preGrant',
        ],
        'pregrantdelete' => [
            'route' => 'licence/irhp-application/application',
            'action' => 'preGrant',
        ],
    ];

    // Scripts to include when rendering actions.
    protected $inlineScripts = [
        'indexAction' => ['table-actions'],
        'preGrantAction' => ['table-actions'],
        'preGrantEditAction' => ['forms/irhp-candidate-permit'],
        'preGrantAddAction' => ['forms/irhp-candidate-permit'],
    ];

    /**
     * Get left view
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function getLeftView()
    {
        $view = new ViewModel(
            [
                'navigationId' => 'licence_irhp_applications-edit',
                'navigationTitle' => 'Application details'
            ]
        );
        $view->setTemplate('admin/sections/admin/partials/generic-left');

        return $view;
    }

    /**
     * @return mixed|Response
     *
     * Small override to handle the cancel button on the Add form as this form is not shown in a JS modal popup
     *
     */
    public function addAction()
    {
        $typeResponse = $this->handleQuery(PermitTypeQry::create(['id' => $this->params()->fromRoute('permitTypeId')]));
        $irhpPermit = $typeResponse->getResult();

        if ($irhpPermit['isApplicationPathEnabled']) {
            $this->questionAnswerAddApplicationRedirect();
        }

        $this->getServiceLocator()->get('Navigation')->findOneBy('id', 'licence_irhp_applications')->setVisible(0);
        $this->setFormTitle($this->params()->fromRoute('permitTypeId', null));
        $request = $this->getRequest();
        if ($request->isPost() && array_key_exists('back', (array)$this->params()->fromPost()['form-actions'])) {
            return $this->permitDashRedirect();
        }

        return parent::addAction();
    }

    /**
     * Handles creation of IrhpApplication rows to support QA Application form rendering.
     *
     * @return Response
     */
    protected function questionAnswerAddApplicationRedirect()
    {
        $response = $this->handleCommand(
            QaCreateDTO::create(
                [
                    'licence' => $this->params()->fromRoute('licence'),
                    'irhpPermitType' => $this->params()->fromRoute('permitTypeId'),
                    'irhpPermitStock' => $this->params()->fromQuery('irhpPermitStock'),
                    'fromInternal' => 1,
                ]
            )
        );
        $result = $response->getResult();

        return $this->redirect()
            ->toRoute(
                'licence/irhp-application/application',
                [
                    'licence' => $this->params()->fromRoute('licence'),
                    'action' => 'edit',
                    'irhpAppId' => $result['id']['irhpApplication']
                ]
            );
    }


    /**
     * @return mixed|Response
     *
     * Small override to handle the cancel button on the Edit form
     *
     */
    public function editAction()
    {
        $request = $this->getRequest();
        if ($request->isPost() && array_key_exists('back', (array)$this->params()->fromPost()['form-actions'])) {
            return $this->permitDashRedirect();
        }

        return parent::editAction();
    }

    /**
     * Sets content tile to identify type of application being submitted
     *
     * @param $permitTypeId
     */
    protected function setFormTitle($permitTypeId)
    {
        $type = '';
        if (array_key_exists($permitTypeId, self::PERMIT_TYPE_LABELS)) {
            $type = self::PERMIT_TYPE_LABELS[$permitTypeId];
        }
        $this->addContentTitle = "Add $type Permit Application";
    }

    /**
     * Dash redirect helper
     *
     * @return Response
     *
     */
    protected function permitDashRedirect()
    {
        return $this->redirect()
            ->toRoute(
                'licence/permits',
                ['licence' => $this->params()->fromRoute('licence')]
            );
    }

    /**
     * Handles click of the Submit button on right-sidebar
     *
     * @return \Zend\Http\Response
     *
     */
    public function submitAction()
    {
        $response = $this->handleQuery(ItemDto::create(['id' => $this->params()->fromRoute('irhpAppId')]));
        $irhpPermit = $response->getResult();

        $feeIds = $this->getOutstandingFeeIds(
            $irhpPermit['fees'],
            [
                RefData::IRHP_GV_APPLICATION_FEE_TYPE,
                RefData::IRHP_GV_ISSUE_FEE_TYPE,
                RefData::IRFO_GV_FEE_TYPE
            ]
        );

        // The application canBeSubmitted, check for an outstanding fee and redirect ICW User to pay screen
        if (!empty($feeIds)) {
            return $this->redirect()
                ->toRoute(
                    'licence/irhp-application-fees/fee_action',
                    [
                        'action' => 'pay-fees',
                        'fee' => implode(',', $feeIds),
                        'licence' => $this->params()->fromRoute('licence'),
                        'irhpAppId' => $this->params()->fromRoute('irhpAppId')
                    ],
                    [],
                    false
                );
        } else {
            // There was no outstanding fee for this application (already been paid) but it is submitable to call handler
            return $this->confirmCommand(
                new ConfirmItem($this->itemParams),
                SubmitApplication::class,
                'Are you sure?',
                'Submit Application. Are you sure?',
                'IRHP Application Submitted'
            );
        }
    }

    /**
     * check for any outstanding fees of the specified types, return the IDs to pass to Fees controller to pay.
     *
     * @param array $fees Array of fees associated with the application
     * @param array $feeTypes Array of fee types of which we need to know if any are outstanding
     *
     * @return array
     */
    protected function getOutstandingFeeIds(array $fees, array $feeTypes)
    {
        $feeIds = [];
        foreach ($fees as $fee) {
            if ($fee['feeStatus']['id'] === RefData::FEE_STATUS_OUTSTANDING
                && in_array($fee['feeType']['feeType']['id'], $feeTypes)) {
                $feeIds[] = $fee['id'];
            }
        }
        return $feeIds;
    }

    /**
     * Handles click of the Cancel button on right sidebar
     *
     * @return \Zend\Http\Response
     *
     */
    public function cancelAction()
    {
        return $this->confirmCommand(
            new ConfirmItem($this->itemParams),
            CancelApplication::class,
            'Are you sure?',
            'Cancel Application. Are you sure?',
            'IRHP Application Cancelled'
        );
    }

    /**
     * withdraw action
     *
     * @return ViewModel
     */
    public function withdrawAction()
    {
        return $this->add(
            WithdrawForm::class,
            new AddFormDefaultData(['id' => $this->params()->fromRoute('irhpAppId')]),
            Withdraw::class,
            \Olcs\Data\Mapper\IrhpWithdraw::class,
            'pages/crud-form',
            'Withdraw Application',
            'Withdraw Application'
        );
    }

    /**
     * Handles click of the Revive Application button on right sidebar
     *
     * @return \Zend\Http\Response
     */
    public function reviveFromWithdrawnAction()
    {
        return $this->confirmCommand(
            new ConfirmItem($this->itemParams),
            ReviveFromWithdrawn::class,
            'Are you sure?',
            'Revive Application from withdrawn state. Are you sure?',
            'IRHP Application revived from withdrawn state'
        );
    }

    /**
     * Handles click of the Revive Application button on right sidebar
     *
     * @return \Zend\Http\Response
     */
    public function reviveFromUnsuccessfulAction()
    {
        return $this->confirmCommand(
            new ConfirmItem($this->itemParams),
            ReviveFromUnsuccessful::class,
            'Are you sure?',
            'Revive Application from unsuccessful state. Are you sure?',
            'IRHP Application revived from unsuccessful state'
        );
    }

    /**
     * Handles click of the Grant button on right sidebar
     *
     * @return \Zend\Http\Response
     *
     */
    public function grantAction()
    {
        return $this->confirmCommand(
            new ConfirmItem($this->itemParams),
            Grant::class,
            'Are you sure?',
            'Grant Application. Are you sure?',
            'IRHP Application Granted'
        );
    }

    /**
     * Setup required values for Add form
     *
     * @param $form
     * @param $formData
     * @return mixed
     * @throws NotFoundException
     */
    protected function alterFormForAdd($form, $formData)
    {
        $licence = $this->getLicence();
        $permitTypeId = $this->params()->fromRoute('permitTypeId', null);
        $formData['topFields']['numVehicles'] = $licence['totAuthVehicles'];
        $formData['topFields']['numVehiclesLabel'] = $licence['totAuthVehicles'];
        $formData['topFields']['dateReceived'] = date("Y-m-d");
        $formData['topFields']['irhpPermitType'] = $permitTypeId;
        $formData['topFields']['licence'] = $this->params()->fromRoute('licence', null);

        $nonQaPermitTypes = [
            RefData::IRHP_BILATERAL_PERMIT_TYPE_ID,
            RefData::IRHP_MULTILATERAL_PERMIT_TYPE_ID,
        ];

        if (in_array($permitTypeId, $nonQaPermitTypes)) {
            $maxStockPermits = $this->handleQuery(
                MaxStockPermits::create(['licence' => $this->params()->fromRoute('licence', null)])
            );
            if (!$maxStockPermits->isOk()) {
                throw new NotFoundException('Could not retrieve max permits data');
            }
            $formData['maxStockPermits']['result'] = $maxStockPermits->getResult()['results'];

            $windows = (int)$formData['topFields']['irhpPermitType'] === (int)RefData::IRHP_BILATERAL_PERMIT_TYPE_ID
                ? $this->getBilateralWindows()['results']
                : $this->getMultilateralWindows()['results'];

            // Prepare data structure with open bilateral windows for NoOfPermits form builder
            $formData['application'] = IrhpApplicationMapper::mapApplicationData(
                $windows,
                $permitTypeId
            );

            // Build the dynamic NoOfPermits per country per year form from Common
            $formData['application']['licence']['totAuthVehicles'] = $licence['totAuthVehicles'];

            $this->getServiceLocator()->get(NoOfPermits::class)->mapForFormOptions(
                $formData,
                $form,
                'application',
                'maxStockPermits',
                'feePerPermit'
            );
        }

        $form->setData($formData);

        $form->get('topFields')->remove('stockHtml');
        $form->get('bottomFields')->remove('checked');

        return $form;
    }

    /**
     * Setup required values for Edit form
     *
     * @param $form
     * @param $formData
     * @return mixed
     *
     * @throws NotFoundException
     */
    protected function alterFormForEdit($form, $formData)
    {
        $licence = $this->getLicence();

        $formData['topFields']['numVehicles'] = $licence['totAuthVehicles'];
        $formData['topFields']['numVehiclesLabel'] = $licence['totAuthVehicles'];
        $formData['topFields']['licence'] = $this->params()->fromRoute('licence', null);

        if (!in_array(
            $formData['topFields']['irhpPermitType'],
            [
                RefData::ECMT_SHORT_TERM_PERMIT_TYPE_ID,
                RefData::ECMT_REMOVAL_PERMIT_TYPE_ID,
                RefData::IRHP_BILATERAL_PERMIT_TYPE_ID,
                RefData::IRHP_MULTILATERAL_PERMIT_TYPE_ID,
                RefData::CERT_ROADWORTHINESS_VEHICLE_PERMIT_TYPE_ID,
                RefData::CERT_ROADWORTHINESS_TRAILER_PERMIT_TYPE_ID
            ]
        )) {
            throw new \RuntimeException('Unsupported Permit Type');
        }

        if ($formData['topFields']['isApplicationPathEnabled']) {
            $form = $this->questionAnswerFormSetup($this->params()->fromRoute('irhpAppId'), $form);
            if ($this->request->isPost()) {
                $formData = $form->updateDataForQa($formData);
            }
        } else {
            $formData = $this->nonQuestionAnswerFormSetup($form, $formData, $licence);
        }

        if (!empty($formData['topFields']['stockText'])) {
            $formData['topFields']['stockHtml'] = $formData['topFields']['stockText'];
        } elseif (!empty($formData['fields']['irhpPermitApplications'][0]['irhpPermitWindow']['irhpPermitStock'])) {
            $irhpPermitStock = $formData['fields']['irhpPermitApplications'][0]['irhpPermitWindow']['irhpPermitStock'];

            $translator = $this->getServiceLocator()->get('Helper\Translation');
            $stockText = sprintf(
                '%s %s',
                $irhpPermitStock['irhpPermitType']['name']['description'],
                !empty($irhpPermitStock['periodNameKey'])
                    ? $translator->translate($irhpPermitStock['periodNameKey'])
                    : $irhpPermitStock['validityYear']
            );
            $formData['topFields']['stockText'] = $formData['topFields']['stockHtml'] = $stockText;
        }

        $form->setData($formData);

        if (!$formData['topFields']['requiresPreAllocationCheck']) {
            $form->get('bottomFields')->remove('checked');
        }

        return $form;
    }

    /**
     * @param Form $form
     * @param array $formData
     * @param array $licence
     * @return mixed
     * @throws NotFoundException
     */
    protected function nonQuestionAnswerFormSetup(Form $form, array $formData, array $licence)
    {
        // Prepare data structure with open bilateral windows for NoOfPermits form builder
        $windows = (int)$formData['topFields']['irhpPermitType'] === RefData::IRHP_BILATERAL_PERMIT_TYPE_ID
            ? $this->getBilateralWindows()['results']
            : $this->getMultilateralWindows()['results'];

        $formData['application'] = IrhpApplicationMapper::mapApplicationData(
            $windows,
            $formData['topFields']['irhpPermitType'],
            $formData
        );

        $maxStockPermits = $this->handleQuery(
            MaxStockPermits::create(['licence' => $this->params()->fromRoute('licence', null)])
        );

        if (!$maxStockPermits->isOk()) {
            throw new NotFoundException('Could not retrieve max permits data');
        }
        $formData['maxStockPermits']['result'] = $maxStockPermits->getResult()['results'];

        // Build the dynamic NoOfPermits per country per year form from Common
        $formData['application']['licence']['totAuthVehicles'] = $licence['totAuthVehicles'];

        $this->getServiceLocator()->get(NoOfPermits::class)->mapForFormOptions(
            $formData,
            $form,
            'application',
            'maxStockPermits',
            'feePerPermit'
        );

        return $formData;
    }

    /**
     * Perform query to obtain application steps for given application ID and populate form.
     *
     * @param int $irhpApplicationId
     * @param Form $form
     * @return mixed
     */
    protected function questionAnswerFormSetup(int $irhpApplicationId, Form $form)
    {
        $response = $this->handleQuery(
            ApplicationPath::create(
                ['id' => $irhpApplicationId]
            )
        );

        $applicationSteps = $response->getResult();

        $fieldsetPopulator = $this->getServiceLocator()->get('QaFieldsetPopulator');
        $fieldsetPopulator->populate($form, $applicationSteps, UsageContext::CONTEXT_INTERNAL);
        return $form;
    }

    /**
     * @return array|mixed
     * @throws NotFoundException
     */
    protected function getBilateralWindows()
    {
        //Get list of countries that BiLaterals are applicable to
        $countries = $this->handleQuery(AvailableCountries::create([]));
        if (!$countries->isOk()) {
            throw new NotFoundException('Could not retrieve available countries');
        }

        // We just want the IDs for the next Query
        $countryIds = array_column($countries->getResult()['countries'], 'id');

        if (empty($countryIds)) {
            throw new NotFoundException('No countries are available for this type of application');
        }

        //Query open windows for the country IDs retrieved above
        $windows = $this->handleQuery(OpenByCountry::create(['countries' => $countryIds]));
        if (!$windows->isOk()) {
            throw new NotFoundException('Could not retrieve open windows');
        }

        return $windows->getResult();
    }

    /**
     * @return array|mixed
     * @throws NotFoundException
     */
    protected function getMultilateralWindows()
    {
        $windows = $this->handleQuery(OpenByType::create(
            [
                'irhpPermitType' => RefData::IRHP_MULTILATERAL_PERMIT_TYPE_ID,
            ]
        ));

        if (!$windows->isOk()) {
            throw new NotFoundException('Could not retrieve open windows');
        }


        return $windows->getResult();
    }

    /**
     * @return array|mixed
     * @throws NotFoundException
     */
    protected function getLicence()
    {
        $response = $this->handleQuery(LicenceDto::create(['id' => $this->params()->fromRoute('licence', null)]));
        if (!$response->isOk()) {
            throw new NotFoundException('Could not find Licence');
        }

        return $response->getResult();
    }

    /**
     *
     * Redirect to relevant action, or return index table of candidate permits
     *
     */
    public function preGrantAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = (array)$this->params()->fromPost();
            switch ($postData['action']) {
                case 'preGrantEdit':
                    return $this->redirect()
                        ->toRoute(
                            'licence/irhp-application/application',
                            [
                                'action' => 'preGrantEdit',
                                'permitId' => $postData['id']
                            ],
                            [],
                            true
                        );
                case 'preGrantDelete':
                    return $this->redirect()
                        ->toRoute(
                            'licence/irhp-application/application',
                            [
                                'action' => 'preGrantDelete',
                                'permitId' => $postData['id']
                            ],
                            [],
                            true
                        );
                case 'preGrantAdd':
                    return $this->redirect()
                        ->toRoute(
                            'licence/irhp-application/application',
                            [ 'action' => 'preGrantAdd'],
                            [],
                            true
                        );
            }
        }

        $this->placeholder()->setPlaceholder('applicationData', IrhpCandidatePermitMapper::mapApplicationData($this->getIrhpApplication()));

        $this->mapperClass = IrhpCandidatePermitMapper::class;

        return $this->index(
            CandidateListDTO::class,
            (new GenericList(['irhpApplication' => 'irhpAppId'], $this->defaultTableSortField, $this->defaultTableOrderField))
                ->setDefaultLimit($this->defaultTableLimit),
            $this->tableViewPlaceholderName,
            'irhp-permits-pre-grant',
            'pages/irhp-permit/pre-grant',
            $this->filterForm
        );
    }

    /**
     * @return array|ViewModel
     */
    public function preGrantEditAction()
    {
        return $this->edit(
            IrhpCandidatePermitForm::class,
            CandidatePermitItemDTO::class,
            new GenericItem(['id' => 'permitId']),
            CandidatePermitUpdateCmd::class,
            IrhpCandidatePermitMapper::class,
            $this->editViewTemplate,
            $this->editSuccessMessage,
            $this->editContentTitle
        );
    }

    /**
     * @return array|mixed|ViewModel
     */
    public function preGrantDeleteAction()
    {
        return $this->confirmCommand(
            new ConfirmItem(['id' => 'permitId']),
            CandidatePermitDeleteCmd::class,
            $this->deleteModalTitle,
            $this->deleteConfirmMessage,
            $this->deleteSuccessMessage
        );
    }

    /**
     * @return mixed|ViewModel
     */
    public function preGrantAddAction()
    {
        return $this->add(
            IrhpCandidatePermitForm::Class,
            new AddFormDefaultData(['irhpPermitApplication' => 34252354]),
            CandidatePermitCreateCmd::class,
            IrhpCandidatePermitMapper::class,
            $this->editViewTemplate,
            $this->addSuccessMessage,
            $this->addContentTitle
        );
    }


    /**
     * Add required parameter to list DTO query
     *
     * @param array $parameters
     * @return array
     */
    protected function modifyListQueryParameters($parameters)
    {
        $parameters['isPreGrant'] = true;
        return $parameters;
    }

    /**
     * Utility function to get IrhpApplication relating to ID in the path.
     *
     * @return array|mixed
     * @throws \RuntimeException
     */
    protected function getIrhpApplication()
    {
        $applicationQry = $this->handleQuery(ItemDto::create(['id' => $this->params()->fromRoute('irhpAppId')]));
        if (!$applicationQry->isOk()) {
            throw new \RuntimeException('Error getting application data');
        }
        return $applicationQry->getResult();
    }

    /**
     * AJAX endpoint to return ranges for a given IrhpApplication's stock
     *
     * @return JsonModel
     * @throws \RuntimeException
     */
    public function rangesAction()
    {
        $rangesQry = $this->handleQuery(RangesDTO::create(['irhpApplication' => $this->params()->fromRoute('irhpAppId')]));
        if (!$rangesQry->isOk()) {
            throw new \RuntimeException('Error getting application data');
        }
        return new JsonModel($rangesQry->getResult());
    }

    /**
     * Generate URL for use on Add/Edit pre-grant form
     *
     * @return string
     */
    protected function getRangesUrl(){
        return $this->url()->fromRoute(
            'licence/irhp-application/application',
            [
                'action' => 'ranges',
            ],
            [],
            true
        );
    }

    /**
     * Get existing application and populate some required fields on Edit form.
     *
     * @param Form $form
     * @param array $formData
     * @return mixed
     */
    protected function alterFormForPreGrantAdd($form, $formData)
    {
        $irhpApplication = $this->getIrhpApplication();
        $form->get('fields')->get('permitAppId')->setValue($irhpApplication['irhpPermitApplications'][0]['id']);
        $form->get('fields')->get('rangesUrl')->setValue($this->getRangesUrl());

        return $form;
    }

    /**
     * Get existing application and populate some required fields on Add form.
     *
     * @param Form $form
     * @param array $formData
     * @return mixed
     */
    protected function alterFormForPreGrantEdit($form, $formData)
    {
        $form->get('fields')->get('rangesUrl')->setValue($this->getRangesUrl());
        $form->setData($formData);
        return $form;
    }
}
