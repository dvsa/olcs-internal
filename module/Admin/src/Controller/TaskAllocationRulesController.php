<?php

namespace Admin\Controller;

use Admin\Form\Model\Form\TaskAllocationRule as FormClass;

use Dvsa\Olcs\Transfer\Command\TaskAllocationRule\Create as CreateDto;
use Dvsa\Olcs\Transfer\Command\TaskAllocationRule\Update as UpdateDto;
use Dvsa\Olcs\Transfer\Command\TaskAllocationRule\DeleteList as DeleteDto;

use Dvsa\Olcs\Transfer\Query\TaskAllocationRule\Get as ItemDto;
use Dvsa\Olcs\Transfer\Query\TaskAllocationRule\GetList as ListDto;
use Olcs\Data\Mapper\TaskAllocationRule as Mapper;
use Olcs\Controller\AbstractInternalController;

use Common\RefData;

/**
 * TaskAllocationRulesController
 */
class TaskAllocationRulesController extends AbstractInternalController
{
    /**
     * Holds the navigation ID,
     * required when an entire controller is
     * represented by a single navigation id.
     */
    protected $navigationId = 'admin-dashboard/task-allocation-rules';

    /**
     * @var array
     */
    protected $inlineScripts = [
        'editAction' => ['table-actions', 'forms/task-alpha-split'],
        'addAction' => ['table-actions', 'forms/task-alpha-split'],
    ];


    // list
    protected $tableName = 'task-allocation-rules';
    protected $defaultTableSortField = 'categoryDescription,criteria,trafficAreaName';
    protected $defaultTableOrderField = 'ASC';

    protected $listDto = ListDto::class;
    protected $tableViewTemplate = 'pages/table';

    // add/edit
    protected $itemDto = ItemDto::class;
    protected $itemParams = ['id'];
    protected $formClass = FormClass::class;
    protected $addFormClass = FormClass::class;
    protected $mapperClass = Mapper::class;
    protected $createCommand = CreateDto::class;
    protected $updateCommand = UpdateDto::class;

    // delete
    protected $deleteParams = ['ids' => 'id'];
    protected $deleteCommand = DeleteDto::class;
    protected $hasMultiDelete = true;
    protected $deleteModalTitle = 'Delete task allocation rule(s)';
    protected $deleteConfirmMessage = 'Are you sure you want to permanently delete the selected task
        allocation rule(s)?';
    protected $deleteSuccessMessage = 'Task allocation rule(s) deleted';

    protected $addContentTitle = 'Add task allocation rule';
    protected $editContentTitle = 'Edit task allocation rule';

    protected $redirectConfig = [
        'addalphasplit' => [
            'action' => 'edit',
            'routeMap' => [
                'id' => 'id'
            ],
            'reUseParams' => false
        ],
        'editalphasplit' => [
            'action' => 'edit',
            'routeMap' => [
                'id' => 'id'
            ],
            'reUseParams' => false
        ],
        'deletealphasplit' => [
            'action' => 'edit',
            'routeMap' => [
                'id' => 'id'
            ],
            'reUseParams' => false
        ],
    ];

    /**
     * Index Action
     *
     * @return \Olcs\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->placeholder()->setPlaceholder('pageTitle', 'Task allocation rules');

        return parent::indexAction();
    }

    /**
     * Overwrite getForm method to inject the table
     *
     * @param $name
     * @return mixed
     */
    public function getForm($name)
    {
        $form = parent::getForm($name);

        if ($name === 'Admin\Form\Model\Form\TaskAllocationRule') {
            $this->getServiceLocator()->get('Helper\Form')->populateFormTable(
                $form->get('details')->get('taskAlphaSplit'), $this->getAlphaSplitTable()
            );
        }

        return $form;
    }

    /**
     * Get the Task alpha split table
     */
    protected function getAlphaSplitTable()
    {
        $tableData = [];
        if (!empty($this->params()->fromRoute('id'))) {
            $data = [
                'taskAllocationRule' => $this->params()->fromRoute('id'),
            ];
            $response = $this->handleQuery(\Dvsa\Olcs\Transfer\Query\TaskAlphaSplit\GetList::create($data));

            if ($response->isServerError() || $response->isClientError()) {
                $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
            }

            if ($response->isOk()) {
                $tableData = $response->getResult();
            }
        }

        return $this->getServiceLocator()->get('Table')->prepareTable('task-alpha-split', $tableData);
    }

    /**
     * Alter the Task allocation rule form when editing
     *
     * @param \Common\Form\Form $form
     * @param array $formData
     *
     * @return \Common\Form\Form
     */
    public function alterFormForEdit(\Common\Form\Form $form, $formData)
    {
        // Setup the initial list of users in the dropdown dependant on the team
        if (isset($formData['details']['team']['id'])) {
            $this->getServiceLocator()->get('Olcs\Service\Data\UserListInternal')
                ->setTeamId($formData['details']['team']['id']);
        }

        /* @var $formHelper \Common\Service\Helper\FormHelperService */
        $formHelper = $this->getServiceLocator()->get('Helper\Form');

        // if there are alpha splits in the table then disable changing team and user
        $taskAlphaSplitCount = count($form->get('details')->get('taskAlphaSplit')->get('table')->getTable()->getRows());
        if ($taskAlphaSplitCount > 0) {
            $formHelper->disableElement($form, 'details->team');
            $formHelper->disableElement($form, 'details->user');
        }

        return $form;
    }

    /**
     * Alter the Task allocation rule form when adding
     *
     * @param Form $form
     *
     * @return Form
     */
    protected function alterFormForAdd($form)
    {
        // remove "alpha split" option from drop down
        $form->get('details')->get('user')->setExtraOption(null);

        // Remove the task alpha split table
        /* @var $formHelper \Common\Service\Helper\FormHelperService */
        $formHelper = $this->getServiceLocator()->get('Helper\Form');
        $formHelper->remove($form, 'details->taskAlphaSplit');

        // isMlh is only required if operator type is goods
        $post = $this->params()->fromPost();
        if (isset($post['details']['goodsOrPsv'])) {
            if ($post['details']['goodsOrPsv'] === RefData::LICENCE_CATEGORY_GOODS_VEHICLE) {
                $form->getInputFilter()->get('details')->get('isMlh')->setRequired(true);
            }
        }

        return $form;
    }

    /**
     * Edit Task allocation rule
     *
     * @return \Olcs\View\Model\ViewModel
     */
    public function editAction()
    {
        $query = $this->params()->fromQuery();

        $tableAction = null;
        if (isset($query['table']['action'])) {
            $tableAction = $query['table']['action'];
            $params = [
                'action' => $tableAction,
                'alpha-split' => $query['id'],
            ];

            // if clicked add alpha split then save any changes made to the rule first
            if (strtolower($tableAction) === 'addalphasplit') {
                $this->saveTaskAllocationRule($query);
            }
            if (isset($query['details']['team'])) {
                $params['team'] = $query['details']['team'];
            } else {
                $params['team'] = $query['details']['teamId'];
            }

            // get the task allocation rule into the new route for add/edit alpha split
            if (isset($query['details']['id'])) {
                $params['id'] = $query['details']['id'];
            }
        }

        if (!$tableAction) {
            return parent::editAction();
        }

        $this->redirect()->toRoute(null, $params, ['code' => 303], true);
    }

    /**
     * Save the task alloaction rule
     *
     * @param array $query
     *
     * @return bool
     */
    private function saveTaskAllocationRule($query)
    {
        $commandData = Mapper::mapFromForm($query);
        $response = $this->handleCommand(UpdateDto::create($commandData));

        return $response->isOk();
    }

    /**
     * Alter form for add alpha split
     *
     * @param Form  $form
     *
     * @return Form
     */
    protected function alterFormForAddAlphasplit($form)
    {
        // Setup the initial list of users dependant on the team
        $this->setUpUserList();

        return $form;
    }

    /**
     * Alter form for add alpha split
     *
     * @param Form  $form
     *
     * @return Form
     */
    protected function alterFormForEditAlphasplit($form)
    {
        // Remove "Save and add another" button
        $formHelper = $this->getServiceLocator()->get('Helper\Form');
        $formHelper->remove($form, 'form-actions->addAnother');

        $this->setUpUserList();

        return $form;
    }

    /**
     * Set up user list
     */
    protected function setUpUserList()
    {
        $teamId = $this->params()->fromRoute('team');
        if ((int) $teamId) {
            $this->getServiceLocator()->get('Olcs\Service\Data\UserListInternal')->setTeamId($teamId);
        }
    }

    /**
     * Add alpha split action
     *
     * @return \Olcs\View\Model\ViewModel
     */
    public function addAlphasplitAction()
    {
        return $this->add(
            \Admin\Form\Model\Form\TaskAlphaSplit::class,
            new \Olcs\Mvc\Controller\ParameterProvider\GenericItem(['taskAllocationRule' => 'id']),
            \Dvsa\Olcs\Transfer\Command\TaskAlphaSplit\Create::class,
            \Olcs\Data\Mapper\TaskAlphaSplit::class,
            $this->editViewTemplate,
            'Alpha split added',
            'Add alpha split'
        );
    }

    /**
     * Edit alpha split action
     *
     * @return \Olcs\View\Model\ViewModel
     */
    public function editAlphasplitAction()
    {
        return $this->edit(
            \Admin\Form\Model\Form\TaskAlphaSplit::class,
            \Dvsa\Olcs\Transfer\Query\TaskAlphaSplit\Get::class,
            new \Olcs\Mvc\Controller\ParameterProvider\GenericItem(['id' => 'alpha-split']),
            \Dvsa\Olcs\Transfer\Command\TaskAlphaSplit\Update::class,
            \Olcs\Data\Mapper\TaskAlphaSplit::class,
            $this->editViewTemplate,
            'Alpha split updated',
            'Edit alpha split'
        );
    }

    /**
     * Delete alpha split action
     *
     * @return \Olcs\View\Model\ViewModel
     */
    public function deleteAlphasplitAction()
    {
        return $this->confirmCommand(
            new \Olcs\Mvc\Controller\ParameterProvider\ConfirmItem(['id' => 'alpha-split']),
            \Dvsa\Olcs\Transfer\Command\TaskAlphaSplit\Delete::class,
            'Delete alpha split',
            'Are you sure you want to remove alpha split?',
            'Alpha split removed'
        );
    }
}
