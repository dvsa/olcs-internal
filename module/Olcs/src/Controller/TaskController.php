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
use Common\Exception\BadRequestException;

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

        // Set up the data services so that dynamic selects populate correctly if we already have data
        if (isset($data['assignedToTeam'])) {
            $this->getServiceLocator()->get('Olcs\Service\Data\User')
                ->setTeam($data['assignedToTeam']);
        }
        if (isset($data['assignment']['assignedToTeam'])) {
            // on POST, the data is nested
            $this->getServiceLocator()->get('Olcs\Service\Data\User')
                ->setTeam($data['assignment']['assignedToTeam']);
        }

        $form = $this->getForm('task-reassign');

        $formData = $this->expandData($data);
        $form->setData($formData);

        $this->formPost($form, 'processAssignTask');

        if ($this->getResponse()->getContent() !== "") {
            return $this->getResponse();
        }

        $this->loadScripts(['forms/task']);

        $view = new ViewModel(['form' => $form]);

        $view->setTemplate('partials/form');
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

        $view->setTemplate('partials/form');
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
                array()
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

        // Set up the data services so that dynamic selects populate correctly if we already have data
        if (isset($data['category'])) {
            $this->getServiceLocator()->get('Olcs\Service\Data\TaskSubCategory')
                ->setCategory($data['category']);
        }
        if (isset($data['assignedToTeam'])) {
            $this->getServiceLocator()->get('Olcs\Service\Data\User')
                ->setTeam($data['assignedToTeam']);
        }

        $form = $this->getForm('task');

        if (isset($data['isClosed']) && $data['isClosed'] === 'Y') {
            $this->disableFormElements($form, ['cancel']);
            $this->setValidateForm(false);
            $textStatus = 'Closed';
        } else {
            $textStatus = 'Open';
        }

        $url = $this->getLinkForTaskForm();

        $details = $form->get('details');

        if ($type === 'Add') {
            $form->get('form-actions')->remove('close');
        }

        $details->get('link')->setValue($url);
        $details->get('status')->setValue('<b>' . $textStatus . '</b>');

        if ($this->isButtonPressed('close')) {
            $type = 'Close';
        }

        if (isset($data['assignedByUser']['contactDetails']['person']['familyName'])) {
            $data['assignedByUserName'] =
                $data['assignedByUser']['contactDetails']['person']['forename'] . ' ' .
                $data['assignedByUser']['contactDetails']['person']['familyName'];
        } else {
            $data['assignedByUserName'] = 'Not set';
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

        $view->setTemplate('partials/form');
        return $this->renderView($view, $type . ' task');
    }

    /**
     * Get task type details
     *
     * @return array
     */
    protected function getTaskTypeDetails()
    {
        $taskId = $this->getFromRoute('task');
        if (empty($taskId)) {
            $taskType    = $this->getFromRoute('type');
            $taskTypeId  = $this->getFromRoute('typeId');
            $linkDisplay = null;
            if (!$taskType) {
                throw new BadRequestException('No task id provided');
            }
        } else {
            // existing task
            $taskDetails = $this->getTaskDetails($taskId);
            $taskTypeId  = $taskDetails['linkId'];
            $linkDisplay = $taskDetails['linkDisplay'];
            // Normalise task type from backend to match route param value
            $taskType = strtolower($taskDetails['linkType']);
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
        }
        return [$taskType, $taskTypeId, $linkDisplay];
    }

    /**
     * Get link to display in add / edit form
     *
     * @return string
     */
    protected function getLinkForTaskForm()
    {
        list($taskType, $taskTypeId, $linkDisplay) = $this->getTaskTypeDetails();

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
                    $linkDisplay ? $linkDisplay : $taskTypeId
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
            case 'case':
                $url = sprintf(
                    '<a href="%s">%s</a>',
                    $this->url()->fromRoute(
                        'case',
                        ['case' => $taskTypeId]
                    ),
                    $linkDisplay ? $linkDisplay : $taskTypeId
                );
                break;
            case 'opposition':
                $url = sprintf(
                    '<a href="%s">%s</a>',
                    $this->url()->fromRoute(
                        'case_opposition',
                        ['case' => $taskTypeId]
                    ),
                    $linkDisplay ? $linkDisplay : $taskTypeId
                );
                break;
            default:
                $url='';
        }
        return $url;
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
     * @return void|redirect
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
        // always use params from route, not the task data!
        $taskType   = $this->getFromRoute('type');
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
            case 'case':
                $route = 'case_processing_tasks';
                $params = ['case' => $taskTypeId];
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
            'assignedToTeam' => 2, // @NOTE: not stubbed yet
        ];

        $taskId = $this->getFromRoute('task');
        if ($taskId) {
            $childProperties = [
                'category',
                'subCategory',
                'assignedToTeam',
                'assignedToUser',
                'assignedDate'
            ];
            $bundle = [
                'children' => [
                    'category',
                    'subCategory',
                    'assignedToTeam',
                    'assignedToUser',
                    'assignedByUser' => [
                        'children' => [
                            'contactDetails' => [
                                'children' => [
                                    'person'
                                ]
                            ]
                        ]
                    ]
                ]
            ];

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
                    'version' => $data['version']
                ]
            );
        }

        if (empty($data['id'])) {
            // adding a new task, add linkage data.
            $taskType    = $this->getFromRoute('type');
            $taskTypeId  = $this->getFromRoute('typeId');
            switch ($taskType) {
                case 'licence':
                    $data['licence'] = $taskTypeId;
                    break;
                case 'application':
                    $data['application'] = $taskTypeId;
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
                case 'case':
                    $data['case'] = $taskTypeId;
                    $case = $this->getCase($taskTypeId);
                    if (isset($case['licence']['id'])) {
                        $data['licence'] = $case['licence']['id'];
                    }
                    if (isset($case['transportManager']['id'])) {
                        $data['transportManager'] = $case['transportManager']['id'];
                    }
                    break;
                default:
                    break;
            }
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
        return [
            'details' => $data,
            'assignment' => $data,
            'assignedBy' => $data,
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
     * Gets the licence by ID.
     *
     * @param int $id
     * @return array
     */
    protected function getLicence($id)
    {
        return $this->getServiceLocator()
            ->get('Entity\Licence')
            ->getOverview($id);
    }

    protected function getLicenceIdForApplication($applicationId)
    {
        return $this->getServiceLocator()
            ->get('Entity\Application')
            ->getLicenceIdForApplication($applicationId);
    }

    protected function getApplication($applicationId)
    {
        return $this->getServiceLocator()
            ->get('Entity\Application')
            ->getDataForTasks($applicationId);
    }

    /**
     * Gets the Bus Registration by ID.
     *
     * @param int $busRegId
     * @return array
     */
    protected function getBusReg($busRegId)
    {
        return $this->getServiceLocator()
            ->get('Entity\BusReg')
            ->getDataForTasks($busRegId);
    }

    /**
     * Gets the case by ID.
     *
     * @param integer $id
     * @return array
     */
    public function getCase($id)
    {
        $service = $this->getServiceLocator()->get('DataServiceManager')->get('Olcs\Service\Data\Cases');
        return $service->fetchCaseData($id);
    }
}
