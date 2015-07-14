<?php

namespace Olcs\Controller\Traits;

use Dvsa\Olcs\Transfer\Query\Task\TaskList;
use Dvsa\Olcs\Transfer\Query\Task\TaskDetails;

/**
 * Task Search Trait
 *
 * @NOTE Migrated
 */
trait TaskSearchTrait
{
    /**
     * Inspect the request to see if we have any filters set, and if necessary, filter them down to a valid subset
     *
     * @param array $extra
     * @return array
     */
    protected function mapTaskFilters(array $extra = [])
    {
        $user = $this->currentUser()->getUserData();

        $defaults = [
            'assignedToUser' => $user['id'],
            'assignedToTeam' => $user['team']['id'],
            'date'  => 'tdt_today',
            'status' => 'tst_open',
            'sort' => 'actionDate',
            'order' => 'ASC',
            'page' => 1,
            'limit' => 10
        ];

        $filters = array_merge(
            $defaults,
            $extra,
            $this->getRequest()->getQuery()->toArray()
        );

        // nuke any empty values too
        return array_filter(
            $filters,
            function ($v) {
                return $v === false || !empty($v);
            }
        );
    }

    /**
     * Get task form
     *
     * @param array $filters
     * @return mixed
     */
    protected function getTaskForm(array $filters = [])
    {
        $form = $this->getForm('TasksHome');

        // the filters generally double up perfectly as form
        // and filter data, but team just needs a little bump...
        if (isset($filters['assignedToTeam'])) {
            $filters['team'] = $filters['assignedToTeam'];
        }

        // @see https://jira.i-env.net/browse/OLCS-6061. Don't worry, filters are ignored
        // if the entity doesn't have the relevant field, so it's safe to cram this in here
        $filters['isTask'] = true;

        // grab all the relevant backend data needed to populate the
        // various dropdowns on the filter form
        $selects = [
            'assignedToTeam' => $this->getListDataFromBackend('Team'),
            'assignedToUser' => $this->getListDataFromBackend('User', $filters, 'loginId'),
            'category' => $this->getListDataFromBackend('Category', [], 'description'),
            'taskSubCategory' => $this->getListDataFromBackend('SubCategory', $filters, 'subCategoryName')
        ];

        // bang the relevant data into the corresponding form inputs
        foreach ($selects as $name => $options) {
            $form->get($name)->setValueOptions($options);
        }

        $form->remove('csrf');

        $form->setData($filters);

        return $form;
    }

    protected function getTaskTable($filters = array(), $noCreate = false)
    {
        $response = $this->handleQuery(TaskList::create($filters));
        $tasks = $response->getResult();

        $options = array_merge($filters, ['query' => $this->getRequest()->getQuery()]);
        $tableName = 'tasks' . ($noCreate ? '-no-create' : '');

        return $this->getTable($tableName, $tasks, $options);
    }

    /**
     * Hold processing of task actions
     *
     * @param string $type
     * @return bool|\Zend\Http\Response
     */
    protected function processTasksActions($type = '')
    {
        if ($this->getRequest()->isPost()) {

            $action = strtolower($this->params()->fromPost('action'));
            if ($action === 're-assign task') {
                $action = 'reassign';
            } elseif ($action === 'create task') {
                $action = 'add';
            } elseif ($action === 'close task') {
                $action = 'close';
            }

            if ($action !== 'add') {
                $id = $this->params()->fromPost('id');

                // pass multiple ids to re-assign or close
                if (($action === 'reassign' || $action === 'close') && is_array($id)) {
                    $id = implode('-', $id);
                }

                // we need only one id to edit
                if ($action === 'edit') {
                    if (!is_array($id) || count($id) !== 1) {
                        throw new \Exception('Please select a single task to edit');
                    }
                    $id = $id[0];
                }
            }

            switch ($type) {
                case 'licence':
                    $params = [
                        'type' => 'licence',
                        'typeId' => $this->params('licence'),
                    ];
                    break;
                case 'application':
                    $params = [
                        'type' => 'application',
                        'typeId' => $this->params('application'),
                    ];
                    break;
                case 'transportManager':
                    $params = [
                        'type' => 'tm',
                        'typeId' => $this->params('transportManager'),
                    ];
                    break;
                case 'busReg':
                    $params = [
                        'type' => 'busreg',
                        'typeId' => $this->params('busRegId'),
                    ];
                    break;
                case 'case':
                    $params = [
                        'type' => 'case',
                        'typeId' => $this->params('case'),
                    ];
                    break;
                default:
                    // no type - call from the home page
                    break;
            }
            $params['action'] = $action;

            if ($action !== 'add') {
                $params['task'] = $id;
            }

            return $this->redirect()->toRoute('task_action', $params);
        }

        return false;
    }

    /**
     * Get task details
     *
     * @param int $id
     * @return array
     */
    protected function getTaskDetails($id = null)
    {
        if (!$id) {
            $id = $this->params('task');
        }

        $response = $this->handleQuery(TaskDetails::create(['id' => $id]));

        return $response->getResult();
    }
}
