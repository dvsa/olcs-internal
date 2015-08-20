<?php

/**
 * Application Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Application;

use Dvsa\Olcs\Transfer\Command\Application\UndoGrant;
use Dvsa\Olcs\Transfer\Query\Application\Application;
use Olcs\Controller\Interfaces\ApplicationControllerInterface;
use Dvsa\Olcs\Transfer\Command\ChangeOfEntity\CreateChangeOfEntity as CreateChangeOfEntityCmd;
use Dvsa\Olcs\Transfer\Command\ChangeOfEntity\DeleteChangeOfEntity as DeleteChangeOfEntityCmd;
use Dvsa\Olcs\Transfer\Command\ChangeOfEntity\UpdateChangeOfEntity as UpdateChangeOfEntityCmd;
use Dvsa\Olcs\Transfer\Query\ChangeOfEntity\ChangeOfEntity as ChangeOfEntityQry;
use Olcs\Controller\AbstractController;
use Olcs\Controller\Traits;
use Zend\View\Model\ViewModel;

/**
 * Application Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class ApplicationController extends AbstractController implements ApplicationControllerInterface
{
    protected $headerViewTemplate = 'partials/application-header.phtml';
    protected $pageLayout = 'application-section';

    use Traits\LicenceControllerTrait,
        Traits\FeesActionTrait,
        Traits\DocumentSearchTrait,
        Traits\DocumentActionTrait,
        Traits\ApplicationControllerTrait;

    /**
     * Route (prefix) for fees action redirects
     * @see Olcs\Controller\Traits\FeesActionTrait
     * @return string
     */
    protected function getFeesRoute()
    {
        return 'lva-application/fees';
    }

    /**
     * The fees route redirect params
     * @see Olcs\Controller\Traits\FeesActionTrait
     * @return array
     */
    protected function getFeesRouteParams()
    {
        return [
            'application' => $this->getFromRoute('application')
        ];
    }

    /**
     * The controller specific fees table params
     * @see Olcs\Controller\Traits\FeesActionTrait
     * @return array
     */
    protected function getFeesTableParams()
    {
        return [
            'licence' => $this->getLicenceIdForApplication(),
            'status' => 'current',
        ];
    }

    /**
     * Placeholder stub
     *
     * @return ViewModel
     */
    public function caseAction()
    {
        $this->checkForCrudAction('case', [], 'case');

        $applicationId = $this->params()->fromRoute('application', null);

        $canHaveCases = $this->getServiceLocator()
            ->get('DataServiceManager')
            ->get('Common\Service\Data\Application')->canHaveCases($applicationId);

        if (!$canHaveCases) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')
                ->addErrorMessage('The application has no cases');

            return $this->redirect()->toRouteAjax('lva-application', array('application' => $applicationId));
        }

        $params = [
            'application' => $applicationId,
            'page'    => $this->params()->fromRoute('page', 1),
            'sort'    => $this->params()->fromRoute('sort', 'id'),
            'order'   => $this->params()->fromRoute('order', 'desc'),
            'limit'   => $this->params()->fromRoute('limit', 10),
        ];

        $params = array_merge(
            $params,
            $this->getRequest()->getQuery()->toArray(),
            array('query' => $this->getRequest()->getQuery())
        );

        $results = $this->getServiceLocator()
            ->get('DataServiceManager')
            ->get('Olcs\Service\Data\Cases')->fetchList($params);

        $view = new ViewModel(['table' => $this->getTable('cases', $results, $params)]);
        $view->setTemplate('partials/table');

        $this->loadScripts(['table-actions']);

        return $this->render($view);
    }

    public function setRequest(\Zend\Http\Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Opposition page
     */
    public function oppositionAction()
    {
        $applicationId = (int) $this->params()->fromRoute('application', null);

        $responseOppositions = $this->handleQuery(
            \Dvsa\Olcs\Transfer\Query\Opposition\OppositionList::create(
                [
                    'application' => $applicationId,
                    'sort' => 'raisedDate',
                    'order' => 'ASC',
                    'page' => 1,
                    'limit' => 1000,
                ]
            )
        );
        if (!$responseOppositions->isOk()) {
            throw new \RuntimeException('Cannot get Opposition list');
        }
        $oppositionResults = $responseOppositions->getResult()['results'];

        /* @var $oppositionHelperService \Common\Service\Helper\OppositionHelperService */
        $oppositionHelperService = $this->getServiceLocator()->get('Helper\Opposition');
        $oppositions = $oppositionHelperService->sortOpenClosed($oppositionResults);

        $responseComplaints = $this->handleQuery(
            \Dvsa\Olcs\Transfer\Query\EnvironmentalComplaint\EnvironmentalComplaintList::create(
                [
                    'application' => $applicationId,
                    'sort' => 'complaintDate',
                    'order' => 'ASC',
                    'page' => 1,
                    'limit' => 1000,
                ]
            )
        );
        if (!$responseComplaints->isOk()) {
            throw new \RuntimeException('Cannot get Complaints list');
        }
        $casesResults = $responseComplaints->getResult()['results'];

        /* @var $complaintsHelperService \Common\Service\Helper\ComplaintsHelperService */
        $complaintsHelperService = $this->getServiceLocator()->get('Helper\Complaints');
        $complaints = $complaintsHelperService->sortCasesOpenClosed($casesResults);

        $view = new ViewModel(
            [
                'tables' => [
                    $this->getTable('opposition-readonly', $oppositions),
                    $this->getTable('environmental-complaints-readonly', $complaints)
                ]
            ]
        );
        $view->setTemplate('pages/multi-tables');

        return $this->renderView($view);
    }

    public function undoGrantAction()
    {
        $request = $this->getRequest();
        $id = $this->params('application');

        if ($request->isPost()) {

            if (!$this->isButtonPressed('cancel')) {

                $response = $this->handleCommand(UndoGrant::create(['id' => $id]));

                if ($response->isOk()) {
                    $this->getServiceLocator()->get('Helper\FlashMessenger')
                        ->addSuccessMessage('The application grant has been undone successfully');
                } else {
                    $this->getServiceLocator()->get('Helper\FlashMessenger')
                        ->addErrorMessage('unknown-error');
                }
            }

            return $this->redirect()->toRouteAjax('lva-application', array('application' => $id));
        }

        $formHelper = $this->getServiceLocator()->get('Helper\Form');

        $form = $formHelper->createFormWithRequest('GenericConfirmation', $request);

        $form->get('messages')->get('message')->setValue('confirm-undo-grant-application');

        $this->pageLayout = null;

        $view = new ViewModel(array('form' => $form));
        $view->setTemplate('partials/form');

        return $this->renderView($view, 'Undo grant application');
    }

    protected function renderLayout($view)
    {
        return $this->render($view);
    }

    protected function getLicenceIdForApplication($applicationId = null)
    {
        if (is_null($applicationId)) {
            $applicationId = $this->params()->fromRoute('application');
        }

        $response = $this->handleQuery(Application::create(['id' => $applicationId]));
        $result = $response->getResult();

        return $result['licence']['id'];
    }

    /**
     * Route (prefix) for document action redirects
     * @see Olcs\Controller\Traits\DocumentActionTrait
     * @return string
     */
    protected function getDocumentRoute()
    {
        return 'lva-application/documents';
    }

    /**
     * Route params for document action redirects
     * @see Olcs\Controller\Traits\DocumentActionTrait
     * @return array
     */
    protected function getDocumentRouteParams()
    {
        return ['application' => $this->getFromRoute('application')];
    }

    /**
     * Get view model for document action
     * @see Olcs\Controller\Traits\DocumentActionTrait
     * @return ViewModel
     */
    protected function getDocumentView()
    {
        $application = $this->getFromRoute('application');
        $licence = $this->getLicenceIdForApplication($application);

        $filters = $this->mapDocumentFilters(['licence' => $licence]);

        $table = $this->getDocumentsTable($filters);
        $form  = $this->getDocumentForm($filters);

        return $this->getViewWithApplication(['table' => $table, 'form'  => $form]);
    }

    /**
     * Action to handle an application change of entity request.
     *
     * @return string|\Zend\Http\Response|ViewModel
     */
    public function changeOfEntityAction()
    {
        $request = $this->getRequest();
        $applicationId = $this->params()->fromRoute('application', null);
        $changeOfEntity = $this->params()->fromRoute('changeId', null);

        if ($this->isButtonPressed('remove')) {
            $dto = DeleteChangeOfEntityCmd::create(['id' => $changeOfEntity]);
            $response = $this->handleCommand($dto);
            if ($response->isOk()) {
                $this->flashMessenger()->addSuccessMessage('application.change-of-entity.delete.success');
            }
            return $this->redirectToRouteAjax(
                'lva-application/overview',
                array(
                    'application' => $applicationId
                )
            );
        }

        $form = $this->getServiceLocator()->get('Helper\Form')
            ->createFormWithRequest('ApplicationChangeOfEntity', $request);

        if (!is_null($changeOfEntity)) {
            $dto = ChangeOfEntityQry::create(['id' => $changeOfEntity]);
            $response = $this->handleQuery($dto);
            $changeOfEntityData = $response->getResult();
            $form->setData(
                array(
                    'change-details' => $changeOfEntityData
                )
            );
        } else {
            $form->get('form-actions')->remove('remove');
        }

        if ($request->isPost()) {
            $form->setData((array)$request->getPost());

            if ($form->isValid()) {

                $details = $form->getData()['change-details'];
                if ($changeOfEntity) {
                    $dto = UpdateChangeOfEntityCmd::create(
                        [
                            'id' => $changeOfEntity,
                            'oldOrganisationName' => $details['oldOrganisationName'],
                            'oldLicenceNo' => $details['oldLicenceNo'],
                        ]
                    );
                } else {
                    $dto = CreateChangeOfEntityCmd::create(
                        [
                            'applicationId' => $applicationId,
                            'oldOrganisationName' => $details['oldOrganisationName'],
                            'oldLicenceNo' => $details['oldLicenceNo'],
                        ]
                    );
                }

                $response = $this->handleCommand($dto);

                if ($response->isOk()) {
                    $this->flashMessenger()->addSuccessMessage('application.change-of-entity.create.success');
                }

                return $this->redirectToRouteAjax(
                    'lva-application/overview',
                    array(
                        'application' => $applicationId
                    )
                );
            }
        }

        $this->pageLayout = null;
        $view = new ViewModel(array('form' => $form));
        $view->setTemplate('partials/form');

        return $this->renderView($view, 'Change Entity');
    }
}
