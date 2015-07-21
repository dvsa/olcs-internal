<?php

/**
 * Transport Manager Details Previous History Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Olcs\Controller\TransportManager\Details;

use Olcs\Controller\TransportManager\Details\AbstractTransportManagerDetailsController;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;
use Common\Controller\Lva\Traits\CrudActionTrait;

/**
 * Transport Manager Details Previous History Controller
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class TransportManagerDetailsPreviousHistoryController extends AbstractTransportManagerDetailsController
{
    use CrudActionTrait;

    /**
     * @var string
     */
    protected $section = 'details-previous-history';

    /**
     * Index action
     *
     * @return ViewModel|Response
     */
    public function indexAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {

            $data = (array)$request->getPost();

            $crudAction = null;
            if (isset($data['convictions'])) {
                $crudAction = $this->getCrudAction([$data['convictions']]);
            } elseif (isset($data['previousLicences'])) {
                $crudAction = $this->getCrudAction([$data['previousLicences']]);
            }

            if ($crudAction !== null) {
                return $this->handleCrudAction(
                    $crudAction,
                    ['add-previous-conviction', 'add-previous-licence'],
                    'id'
                );
            }
        }

        $this->loadScripts(['forms/crud-table-handler', 'tm-previous-history']);

        $form = $this->getPreviousHistoryForm();

        $view = $this->getViewWithTm(['form' => $form]);
        $view->setTemplate('pages/form');

        return $this->renderView($view);
    }

    protected function getPreviousHistoryForm()
    {
        $formHelper = $this->getServiceLocator()->get('Helper\Form');

        $form = $formHelper->createForm('TmPreviousHistory');

        $this->getServiceLocator()->get('Helper\TransportManager')
            ->alterPreviousHistoryFieldset($form->get('previousHistory'), $this->params('transportManager'));

        return $form;
    }

    /**
     * Delete previous conviction action
     */
    public function deletePreviousConvictionAction()
    {
        return $this->deleteRecordsCommand(
            \Dvsa\Olcs\Transfer\Command\PreviousConviction\DeletePreviousConviction::class
        );
    }

    /**
     * Delete previous licence action
     */
    public function deletePreviousLicenceAction()
    {
        return $this->deleteRecordsCommand(\Dvsa\Olcs\Transfer\Command\OtherLicence\DeleteOtherLicence::class);
    }

    /**
     * Add previous conviction action
     *
     * @return mixed
     */
    public function addPreviousConvictionAction()
    {
        return $this->formAction('Add', 'TmConvictionsAndPenalties');
    }

    /**
     * Edit previous conviction action
     *
     * @return mixed
     */
    public function editPreviousConvictionAction()
    {
        return $this->formAction('Edit', 'TmConvictionsAndPenalties');
    }

    /**
     * Add previous licence action
     *
     * @return mixed
     */
    public function addPreviousLicenceAction()
    {
        return $this->formAction('Add', 'TmPreviousLicences');
    }

    /**
     * Edit previous licence action
     *
     * @return mixed
     */
    public function editPreviousLicenceAction()
    {
        return $this->formAction('Edit', 'TmPreviousLicences');
    }

    /**
     * Form action
     *
     * @param string $type
     * @param string $formName
     * @return mixed
     */
    protected function formAction($type, $formName)
    {
        if ($this->isButtonPressed('cancel')) {
            return $this->redirectToIndex();
        }

        $form = $this->alterForm($this->getForm($formName), $type);

        if (!$this->getRequest()->isPost()) {
            $form = $this->populateEditForm($form, $formName);
        }

        $this->formPost($form, 'processForm');

        if ($this->getResponse()->getContent() !== "") {
            return $this->getResponse();
        }

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('partials/form');

        return $this->renderView(
            $view,
            $type . ($formName == 'TmConvictionsAndPenalties' ? ' previous conviction' : ' previous licence')
        );
    }

    /**
     * Alter form
     *
     * @param Zend\Form\Form $form
     * @param string $type
     * @return Zend\Form\Form
     */
    protected function alterForm($form, $type)
    {
        if ($type !== 'Add') {
            $this->getServiceLocator()->get('Helper\Form')->remove($form, 'form-actions->addAnother');
        }
        return $form;
    }

    /**
     * Populate edit form
     *
     * @param Zend\Form\Form $form
     * @return Zend\Form\Form
     */
    protected function populateEditForm($form, $formName)
    {
        $id = $this->getFromRoute('id');

        $data = [];
        if ($formName == 'TmConvictionsAndPenalties') {
            if (is_numeric($id)) {
                $response = $this->handleQuery(
                    \Dvsa\Olcs\Transfer\Query\PreviousConviction\PreviousConviction::create(['id' => $id])
                );
                if (!$response->isOk()) {
                    throw new \RuntimeException('Error getting OtherLicence');
                }
                $data = $response->getResult();
            }
            $dataPrepared = [
                'tm-convictions-and-penalties-details' => $data
            ];
        } else {
            if (is_numeric($id)) {
                $response = $this->handleQuery(
                    \Dvsa\Olcs\Transfer\Query\OtherLicence\OtherLicence::create(['id' => $id])
                );
                if (!$response->isOk()) {
                    throw new \RuntimeException('Error getting OtherLicence');
                }
                $data = $response->getResult();
            }
            $dataPrepared = [
                'tm-previous-licences-details' => $data
            ];
        }
        $form->setData($dataPrepared);
        return $form;
    }

    /**
     * Process form and redirect back to list
     *
     * @param array $data
     * @return redirect
     */
    protected function processForm($data)
    {
        if (isset($data['tm-convictions-and-penalties-details'])) {
            $this->savePreviousConviction($data['tm-convictions-and-penalties-details']);
            $action = 'add-previous-conviction';
        } else {
            $this->saveOtherLicence($data['tm-previous-licences-details']);
            $action = 'add-previous-licence';
        }

        if ($this->isButtonPressed('addAnother')) {
            $routeParams = [
                'transportManager' => $this->fromRoute('transportManager'),
                'action' => $action
            ];
            return $this->redirect()->toRoute(null, $routeParams);
        } else {
            return $this->redirectToIndex();
        }
    }

    /**
     * Save an OtherLicence
     *
     * @param array $data array keys "id", "version", "licNo", "holderName"
     *
     * @throws \RuntimeException
     */
    private function saveOtherLicence($data)
    {
        if (is_numeric($data['id'])) {
            // update
            $command = \Dvsa\Olcs\Transfer\Command\OtherLicence\UpdateForTma::create($data);
        } else {
            // create
            $data['transportManagerId'] = $this->getFromRoute('transportManager');
            $command = \Dvsa\Olcs\Transfer\Command\OtherLicence\CreateForTm::create($data);
        }

        $response = $this->handleCommand($command);
        if (!$response->isOk()) {
            throw new \RuntimeException('Error saving OtherLicence');
        }
    }

    /**
     * Save an PreviousConviction
     *
     * @param array $data array keys "id", "version", "convictionDate", etc
     *
     * @throws \RuntimeException
     */
    private function savePreviousConviction($data)
    {
        if (is_numeric($data['id'])) {
            // update
            $command = \Dvsa\Olcs\Transfer\Command\PreviousConviction\UpdatePreviousConviction::create($data);
        } else {
            // create
            $data['transportManager'] = $this->getFromRoute('transportManager');
            $command = \Dvsa\Olcs\Transfer\Command\PreviousConviction\CreatePreviousConviction::create($data);
        }

        $response = $this->handleCommand($command);
        if (!$response->isOk()) {
            throw new \RuntimeException('Error saving PreviousConviction');
        }
    }
}
