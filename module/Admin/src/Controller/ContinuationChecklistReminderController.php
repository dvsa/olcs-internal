<?php

namespace Admin\Controller;

use Common\Controller\Lva\Traits\CrudActionTrait;
use Common\Service\Helper\DateHelperService;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\ResponseHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableBuilder;
use Dvsa\Olcs\Transfer\Command\ContinuationDetail\Queue as QueueCmd;
use Dvsa\Olcs\Transfer\Query\ContinuationDetail\ChecklistReminders as ChecklistRemindersQry;
use Laminas\Form\Form;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\View\HelperPluginManager;
use Laminas\View\Model\ViewModel;

/**
 * ContinuationChecklistReminderController
 *
 * @author Mat Evans <mat.evans@valtech.co.uk>
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class ContinuationChecklistReminderController extends AbstractController
{
    const TYPE_CONT_CHECKLIST_REMINDER_GENERATE_LETTER = 'que_typ_cont_check_rem_gen_let';

    use CrudActionTrait;

    protected DateHelperService $dateHelperService;
    protected FlashMessengerHelperService $flashMessengerHelperService;
    protected FormHelperService $formHelperService;
    protected ResponseHelperService $responseHelperService;
    protected TableBuilder $tableBuilder;
    protected ScriptFactory $scriptFactory;

    public function __construct(
        DateHelperService $dateHelperService,
        FlashMessengerHelperService $flashMessengerHelperService,
        FormHelperService $formHelperService,
        ResponseHelperService $responseHelperService,
        TableBuilder $tableBuilder,
        ScriptFactory $scriptFactory,
        HelperPluginManager $viewHelperPluginManager
    ) {
        $this->dateHelperService = $dateHelperService;
        $this->flashMessengerHelperService = $flashMessengerHelperService;
        $this->formHelperService = $formHelperService;
        $this->responseHelperService = $responseHelperService;
        $this->tableBuilder = $tableBuilder;
        $this->scriptFactory = $scriptFactory;
        parent::__construct($viewHelperPluginManager);
    }

    /**
     * Display a list of Continuation checklist reminders
     *
     * @return ViewModel|Response
     */
    public function indexAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = (array) $request->getPost();

            $crudAction = $this->getCrudAction([$data]);
            if ($crudAction !== null) {
                return $this->handleCrudAction($crudAction);
            }
        }

        $nowDate = $this->dateHelperService->getDate('Y-m');
        list($year, $month) = explode('-', $nowDate);

        $filterForm = $this->getChecklistReminderFilterForm($month, $year);
        if ($filterForm->isValid()) {
            list($year, $month) = explode('-', $filterForm->getData()['filters']['date']);
        }

        $response = $this->handleQuery(
            ChecklistRemindersQry::create(
                [
                    'month' => $month,
                    'year' => $year
                ]
            )
        );
        if ($response->isServerError() || $response->isClientError()) {
            $this->flashMessengerHelperService->addErrorMessage('unknown-error');
        }
        $results = [];
        $total = 0;
        if ($response->isOk()) {
            $results = $response->getResult()['results'];
            $total = $response->getResult()['count'];
        }

        $table = $this->getTable($results);
        $subTitle = date('M Y', strtotime($year . '-' . $month . '-01'));
        $table->setVariable('title', $subTitle .': '. $total . ' licence(s)');

        $this->scriptFactory->loadFiles(['forms/filter', 'forms/crud-table-handler']);

        $view = new ViewModel(['table' => $table, 'filterForm' => $filterForm]);
        $view->setTemplate('pages/table');

        $this->viewHelperPluginManager->get('placeholder')
            ->getContainer('tableFilters')->set($filterForm);
        $this->setNavigationId('admin-dashboard/continuations');

        return $this->renderView($view, 'admin-generate-continuation-details-title');
    }

    /**
     * Get the filter form
     *
     * @param int $defaultMonth Default month
     * @param int $defaultYear  Default year
     *
     * @return Form
     */
    protected function getChecklistReminderFilterForm($defaultMonth, $defaultYear)
    {
        $queryData = (array) $this->params()->fromQuery('filters');
        $defaults = [
            'date' => [
                'month' => $defaultMonth,
                'year' => $defaultYear,
            ]
        ];

        $filters = array_merge($defaults, $queryData);

        $formHelper = $this->formHelperService;
        $form = $formHelper->createForm('ChecklistReminderFilter', false)
            ->setData(['filters' => $filters]);

        if (empty($queryData)) {
            $formHelper->restoreFormState($form);
        } else {
            $formHelper->saveFormState($form, ['filters' => $filters]);
        }

        return $form;
    }

    /**
     * Generate Continuation checklist reminder letters
     *
     * @return Response
     */
    public function generateLettersAction()
    {
        $continuationDetailIds = explode(',', $this->params('child_id'));

        $response = $this->handleCommand(
            QueueCmd::create(
                [
                    'ids' => $continuationDetailIds,
                    'type' => self::TYPE_CONT_CHECKLIST_REMINDER_GENERATE_LETTER
                ]
            )
        );
        $flashMessenger = $this->flashMessengerHelperService;
        if ($response->isClientError() || $response->isServerError()) {
            $flashMessenger->addErrorMessage('The checklist reminder letters could not be generated, please try again');
        }

        if ($response->isOk()) {
            $flashMessenger->addSuccessMessage('The checklist reminder letters have been generated.');
        }

        return $this->redirect()->toRouteAjax(null, ['action' => null, 'child_id' => null], [], true);
    }

    /**
     * Export table as CSV action
     *
     * @return Response
     */
    public function exportAction()
    {
        $continuationDetailIds = explode(',', $this->params('child_id'));

        $response = $this->handleQuery(
            ChecklistRemindersQry::create(['ids' => $continuationDetailIds])
        );
        if ($response->isServerError() || $response->isClientError()) {
            $this->flashMessengerHelperService->addErrorMessage('unknown-error');
            return $this->redirect()->toRouteAjax(null, ['action' => null, 'child_id' => null], [], true);
        }
        $results = [];
        if ($response->isOk()) {
            $results = $response->getResult()['results'];
        }

        $table = $this->getTable($results);

        $helper = $this->responseHelperService;
        return $helper->tableToCsv($this->getResponse(), $table, 'Checklist reminder list');
    }

    /**
     * Get the table with populated data
     *
     * @param array $results Array of entity data
     *
     * @return TableBuilder
     */
    protected function getTable($results)
    {
        $table = $this->tableBuilder
            ->prepareTable('admin-continuations-checklist', $results);

        return $table;
    }
}
