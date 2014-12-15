<?php

/**
 * Task Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */

namespace Olcs\Controller;

use Zend\View\Model\ViewModel;
use Olcs\Controller\Traits\TaskSearchTrait;

/**
 * Task Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class TaskController extends AbstractController
{
    /**
     * Need to get some base task type details
     */
    use TaskSearchTrait;

    /**
     * Add a new task
     *
     * @return ViewModel
     */
    public function addAction()
    {
        return $this->formAction('Add');
    }

    /**
     * Edit a task
     *
     * @return ViewModel
     */
    public function editAction()
    {
        return $this->formAction('Edit');
    }

    /**
     * Re-assign one or several tasks to a different team/user
     *
     * @return ViewModel
     */
    public function reassignAction()
    {
        $data = $this->mapDefaultData();
        $filters = $this->mapFilters($data);

        $form = $this->getForm('task');
        $form->remove('details');
        $inputs = array(
            'assignedToTeam' => $this->getListDataFromBackend('Team'),
            'assignedToUser' => $this->getListDataFromBackend('User', $filters, 'name', 'id', 'Unassigned')
        );
        foreach ($inputs as $name => $options) {
            $form->get('assignment')
                ->get($name)
                ->setValueOptions($options);
        }
        $form->setData($this->expandData($data));

        $this->formPost($form, 'processAssignTask');

        if ($this->getResponse()->getContent() !== "") {
            return $this->getResponse();
        }

        $this->loadScripts(['forms/task']);

        $view = new ViewModel(
            [
                'form' => $form
            ]
        );

        $view->setTemplate('task/add-or-edit');
        $tasks = $this->getFromRoute('task');
        $tasksCount = count(explode('-', $tasks));
        $formTitle = ($tasksCount == 1) ? 'Re-assign task' : "Re-assign ($tasksCount) tasks";
        return $this->renderView($view, $formTitle);
    }

    /**
     * Close one or several tasks to a different team/user
     *
     * @return ViewModel
     */
    public function closeAction()
    {
        $data = $this->mapDefaultData();
        $this->mapFilters($data);

        $form = $this->getForm('task-close');
        $this->formPost($form, 'processCloseTask');

        if ($this->getResponse()->getContent() !== "") {
            return $this->getResponse();
        }

        $tasks = $this->getFromRoute('task');
        $tasksCount = count(explode('-', $tasks));
        if ($tasksCount > 1) {
            $form->get('details')->setLabel('tasks.close.multiple');
        }

        $this->loadScripts(['forms/task']);

        $view = new ViewModel(
            [
                'form' => $form
            ]
        );

        $view->setTemplate('task/add-or-edit');
        $formTitle = ($tasksCount == 1) ? 'Close task' : "Close ($tasksCount) tasks";
        return $this->renderView($view, $formTitle);
    }

    /**
     * Callback invoked when the form is valid
     *
     * @param array $data
     */
    public function processCloseTask($data)
    {
        $ids = explode('-', $this->getFromRoute('task'));
        foreach ($ids as $id) {
            $version = ($id == $data['id']) ? $data['version'] : $this->getTaskVersion($id);
            $this->makeRestCall(
                'Task',
                'PUT',
                array(
                    'id' => $id,
                    'version' => $version,
                    'isClosed' => 'Y'
                )
            );
        }
        $this->redirectToList();
    }

    /**
     * Callback invoked when the form is valid
     *
     * @param array $data
     */
    public function processAssignTask($data)
    {
        if (isset($data['assignment'])) {
            $assignment = $data['assignment'];
            $user = $assignment['assignedToUser'];
            $team = $assignment['assignedToTeam'];
            $ids = explode('-', $this->getFromRoute('task'));
            foreach ($ids as $id) {
                $version = ($id == $data['id']) ? $data['version'] : $this->getTaskVersion($id);
                $this->makeRestCall(
                    'Task',
                    'PUT',
                    array(
                        'id' => $id,
                        'version' => $version,
                        'assignedToUser' => $user,
                        'assignedToTeam' => $team
                    )
                );
            }
        }
        $this->redirectToList();
    }

    /**
     * Get task version
     *
     * @param int $id
     * @return int
     */
    private function getTaskVersion($id)
    {
        $version = 0;
        if ($id) {
            $task = $this->makeRestCall(
                'Task',
                'GET',
                array('id' => $id),
                array('properties' => array('version'))
            );
            if (isset($task['version'])) {
                $version = $task['version'];
            }
        }
        return $version;
    }

    /**
     * Set up and post form
     *
     * @param string $type
     * @return View
     */
    private function formAction($type)
    {
        $data = $this->mapDefaultData();

        $filters = $this->mapFilters($data);

        $form = $this->getForm('task');

        $selects = array(
            'details' => array(
                'category' => $this->getListDataFromBackend('Category', [], 'description'),
                'subCategory' => $this->getListDataFromBackend('SubCategory', $filters, 'subCategoryName')
            ),
            'assignment' => array(
                'assignedToTeam' => $this->getListDataFromBackend('Team'),
                'assignedToUser' => $this->getListDataFromBackend('User', $filters, 'name', 'id', 'Unassigned')
            )
        );

        foreach ($selects as $fieldset => $inputs) {
            foreach ($inputs as $name => $options) {
                $form->get($fieldset)
                    ->get($name)
                    ->setValueOptions($options);
            }
        }

        if (isset($data['isClosed']) && $data['isClosed'] === 'Y') {
            $this->disableFormElements($form, ['cancel']);
            $this->setValidateForm(false);
            $textStatus = 'Closed';
        } else {
            $textStatus = 'Open';
        }

        $details = $form->get('details');

        $url = $this->getLinkForTaskForm();
        $details->get('link')->setValue($url);
        $details->get('status')->setValue('<b>' . $textStatus . '</b>');

        if ($this->isButtonPressed('close')) {
            $type = 'Close';
        }

        $form->setData($this->expandData($data));
        $this->formPost($form, 'process' . $type . 'Task');

        // we have to allow for the fact that our process callback has
        // already set some response data. If so, respect it and
        // bail out early
        if ($this->getResponse()->getContent() !== "") {
            return $this->getResponse();
        }

        $this->loadScripts(['forms/task']);

        $view = new ViewModel(
            [
                'form' => $form
            ]
        );

        $view->setTemplate('task/add-or-edit');
        return $this->renderView($view, $type . ' task');
    }

    /**
     * Get link to display in add / edit form
     *
     * @return string
     */
    protected function getLinkForTaskForm()
    {
        $taskTypeDetails = $this->getTaskTypeDetails();
        $taskType        = str_replace(' ', '', $taskTypeDetails['taskType']);
        $taskTypeId      = $taskTypeDetails['taskTypeId'];
        $linkDisplay     = $taskTypeDetails['linkDisplay'];

        switch ($taskType) {
            case 'licence':
                if (!$linkDisplay) {
                    $licence = $this->getLicence($taskTypeId);
                }
                $url = sprintf(
                    '<a href="%s">%s</a>',
                    $this->url()->fromRoute('lva-licence', ['licence' => $taskTypeId]),
                    $linkDisplay ? $linkDisplay : $licence['licNo']
                );
                break;
            case 'application':
                $application = $this->getApplication($taskTypeId);
                $licenceId   = $application['licence']['id'];
                $licNo       = $application['licence']['licNo'];

                $url = sprintf(
                    '<a href="%s">%s</a> / <a href="%s">%s</a>',
                    $this->url()->fromRoute('lva-licence', ['licence' => $licenceId]),
                    $licNo,
                    $this->url()->fromRoute('lva-application', ['application' => $taskTypeId]),
                    $taskTypeId
                );
                break;
            case 'tm':
                $url = sprintf(
                    '<a href="%s">%s</a>',
                    $this->url()->fromRoute('transport-manager', ['transportManager' => $taskTypeId]),
                    $linkDisplay
                );
                break;
            case 'busreg':
                $busReg = $this->getBusReg($taskTypeId);
                $licenceId = $busReg['licence']['id'];
                $url = sprintf(
                    '<a href="%s">%s</a>',
                    $this->url()->fromRoute(
                        'licence/bus-details',
                        ['busRegId' => $taskTypeId, 'licence' => $licenceId]
                    ),
                    $linkDisplay ? $linkDisplay : $busReg['regNo']
                );
                break;
            default:
                $url='';
        }
        return $url;
    }

    /**
     * Override the parent getListDataFromBackend method simply to save us constantly having to
     * supply the $showAll parameter as 'Please select'
     */
    protected function getListDataFromBackend(
        $entity,
        $data = array(),
        $titleKey = 'name',
        $primaryKey = 'id',
        $showAll = 'Please select'
    ) {
        return parent::getListDataFromBackend($entity, $data, $titleKey, $primaryKey, $showAll);
    }

    /**
     * Callback invoked when the form is valid
     *
     * @param array $data
     * @return void|redirect
     */
    public function processAddTask($data)
    {
        return $this->processForm($data, 'Add');
    }

    /**
     * Callback invoked when the form is valid
     *
     * @param array $data
     * @return void|redirect
     */
    public function processEditTask($data)
    {
        return $this->processForm($data, 'Edit');
    }

    /**
     * Process form and redirect back to list
     *
     * @param array $data
     * @param string $type
     * @return voide|redirect
     */
    private function processForm($data, $type)
    {
        $data = $this->flattenData($data);

        $method = 'process' . $type;

        if ($this->isButtonPressed('cancel')) {
            $this->redirectToList();
        }

        $result = $this->$method($data, 'Task');

        if ($type === 'Edit' || isset($result['id'])) {
            $this->redirectToList();
        }
    }

    /**
     * Redirect back to list of tasks
     *
     * @return redirect
     */
    public function redirectToList()
    {
        $taskType = $this->getFromRoute('type');
        $taskTypeId = $this->getFromRoute('typeId');
        switch ($taskType) {
            case 'licence':
                $route = 'licence/processing';
                $params = ['licence' => $taskTypeId];
                break;
            case 'application':
                $route = 'lva-application/processing';
                $params = ['application' => $taskTypeId];
                break;
            case 'tm':
                $route = 'transport-manager/processing/tasks';
                $params = ['transportManager' => $taskTypeId];
                break;
            case 'busreg':
                $route = 'licence/bus-processing/tasks';
                $busReg = $this->getBusReg($taskTypeId);
                $licenceId = $busReg['licence']['id'];
                $params = ['busRegId' => $taskTypeId, 'licence' => $licenceId];
                break;
            default:
                // no type - call from the home page, need to redirect back after action
                $route = 'dashboard';
                $params = [];
                break;
        }

        return $this->redirect()->toRouteAjax($route, $params);
    }

    /**
     * Merge some sensible default dropdown values with any POST data we may have
     *
     * @return array
     */
    private function mapDefaultData()
    {
        $defaults = [
            'assignedToUser' => $this->getLoggedInUser(),
            'assignedToTeam' => 2 // @NOTE: not stubbed yet
        ];

        $taskId = $this->getFromRoute('task');
        if ($taskId) {
            $childProperties = [
                'category', 'subCategory',
                'assignedToTeam', 'assignedToUser'
            ];
            $bundle = [
                'children' => []
            ];
            foreach ($childProperties as $child) {
                $bundle['children'][$child] = [
                    'properties' => ['id']
                ];
            }

            $resource = $this->makeRestCall(
                'Task',
                'GET',
                ['id' => $taskId],
                $bundle
            );

            foreach ($childProperties as $child) {
                if (isset($resource[$child]['id'])) {
                    $resource[$child] = $resource[$child]['id'];
                } else {
                    $resource[$child] = null;
                }
            }
        } else {
            $resource = [];
        }

        $data = $this->flattenData(
            $this->getRequest()->getPost()->toArray()
        );

        return array_merge(
            $defaults,
            $resource,
            $data
        );
    }

    /**
     * Map some flattened data into relevant dropdown filters
     *
     * @param $data array
     * @return array
     */
    private function mapFilters($data)
    {
        $filters = [];

        if (!empty($data['assignedToTeam'])) {
            $filters['team'] = $data['assignedToTeam'];
        }
        if (!empty($data['category'])) {
            $filters['category'] = $data['category'];
        }

        $filters['isTask'] = true;

        return $filters;
    }

    /**
     * Flatten nested fieldset data into a collapsed array
     *
     * @param array $data
     * @return array
     */
    private function flattenData($data)
    {
        if (isset($data['details']) && isset($data['assignment'])) {
            $data = array_merge(
                $data['details'],
                $data['assignment'],
                [
                    'id' => $data['id'],
                    'version' => $data['version'],
                    'buttonClicked' => isset($data['buttonClicked']) ? $data['buttonClicked'] : ''
                ]
            );
        }
        $taskTypeDetails = $this->getTaskTypeDetails();
        $taskType = $taskTypeDetails['taskType'];
        $taskTypeId = $taskTypeDetails['taskTypeId'];
        switch ($taskType) {
            case 'licence':
                $data['licence'] = $taskTypeId;
                break;
            case 'application':
                $data['application'] = $taskTypeId;
                // bit ugly, but we need the licenceId too to properly link the task
                $data['licence'] = $this->getLicenceIdForApplication($taskTypeId);
                break;
            case 'tm':
                $data['transportManager'] = $taskTypeId;
                break;
            case 'busreg':
                $data['busReg']  = $taskTypeId;
                $busReg = $this->getBusReg($taskTypeId);
                $data['licence'] = $busReg['licence']['id'];
                break;
            default:
                break;
        }
        if (isset($data['urgent'])) {
            $data['urgent'] = $data['urgent'] == '1' ? 'Y' : 'N';
        }

        return $data;
    }

    /**
     * Expand a flattened array of data into form fieldsets
     *
     * @param array $data
     * @return array
     */
    private function expandData($data)
    {
        if (isset($data['urgent'])) {
            $data['urgent'] = $data['urgent'] === 'Y' ? 1 : 0;
        }

        return [
            'details' => $data,
            'assignment' => $data,
            'id' => isset($data['id']) ? $data['id'] : '',
            'version' => isset($data['version']) ? $data['version'] : ''
        ];
    }

    /**
     * Disable form elements
     *
     * @param Zend\Form\Element
     * @param array $exclude
     */
    public function disableFormElements($element, $exclude = [])
    {
        if (in_array($element->getName(), $exclude)) {
            return;
        }

        if ($element instanceof \Zend\Form\Fieldset) {
            foreach ($element->getFieldsets() as $child) {
                $this->disableFormElements($child, $exclude);
            }

            foreach ($element->getElements() as $child) {
                $this->disableFormElements($child, $exclude);
            }
        }

        if ($element instanceof \Zend\Form\Element\DateSelect) {
            $this->disableFormElements($element->getDayElement(), $exclude);
            $this->disableFormElements($element->getMonthElement(), $exclude);
            $this->disableFormElements($element->getYearElement(), $exclude);
        }

        $element->setAttribute('disabled', 'disabled');
    }

    /**
     * Get task type details
     *
     * @return array
     */
    public function getTaskTypeDetails()
    {
        $taskType    = $this->getFromRoute('type');
        $taskTypeId  = $this->getFromRoute('typeId');
        $linkDisplay = null;

        /* if call was from home page we don't have a task type yet,
         * need to get it and all details for url generation as well
         */
        if (!$taskType) {
            $taskId = $this->getFromRoute('task');
            if (!$taskId) {
                throw new \Exception('No task id provided');
            }

            $taskDetails = $this->getTaskDetails($taskId);
            $taskType = strtolower($taskDetails['linkType']);
            $taskTypeId = $taskDetails['linkId'];
            $linkDisplay = $taskDetails['linkDisplay'];
        }

        // Normalise task type from backend with route param version. Ugh.
        switch ($taskType) {
            case 'transport manager':
                $taskType = 'tm';
                break;
            case 'bus registration':
                $taskType = 'busreg';
                break;
            default:
                break;
        }

        return [
            'taskType'    => $taskType,
            'taskTypeId'  => $taskTypeId,
            'linkDisplay' => $linkDisplay,
        ];
    }

    /**
     * Gets the licence by ID.
     *
     * @param int $id
     * @return array
     */
    protected function getLicence($id)
    {
        $licence = $this->makeRestCall('Licence', 'GET', array('id' => $id));

        return $licence;
    }

    /**
     * @todo optimise this to reuse getApplication data if we already called 
     * the backend
     */
    protected function getLicenceIdForApplication($applicationId = null)
    {
        $application = $this->getApplication($applicationId);
        return $application['licence']['id'];
    }

    protected function getApplication($applicationId = null)
    {
        if (is_null($applicationId)) {
            $applicationId = $this->params()->fromRoute('application');
        }
        return $this->getServiceLocator()
            ->get('Entity\Application')
            ->getDataForTasks($applicationId);
    }

    /**
     * Gets the Bus Registration by ID.
     *
     * @param int $id
     * @return array
     */
    protected function getBusReg($id)
    {

        $bundle = [
            'children' => [
                'licence' => [
                    'properties' => ['id'],
                ],
            ]
        ];

        return $this->makeRestCall('BusReg', 'GET', array('id' => $id, 'bundle' => json_encode($bundle)));
    }
}
