<?php

namespace Olcs\Controller;

use Common\Service\Helper\FormHelperService;
use Dvsa\Olcs\Transfer\Command\Task\CloseTasks;
use Dvsa\Olcs\Transfer\Command\Task\CreateTask;
use Dvsa\Olcs\Transfer\Command\Task\ReassignTasks;
use Dvsa\Olcs\Transfer\Command\Task\UpdateTask;
use Dvsa\Olcs\Transfer\Query\Application\Application;
use Dvsa\Olcs\Transfer\Query\Cases\Cases;
use Dvsa\Olcs\Transfer\Query\Licence\Licence;
use Dvsa\Olcs\Transfer\Query\Task\Task;
use Zend\View\Model\ViewModel;
use Olcs\Controller\Traits as ControllerTraits;
use Common\Exception\BadRequestException;

/**
 * Task Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class TaskController extends AbstractController
{
    use ControllerTraits\BusControllerTrait,
        ControllerTraits\TaskSearchTrait;

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

        // Set up the data services so that dynamic selects populate correctly if we already have data
        if (isset($data['assignedToTeam'])) {
            $this->getServiceLocator()->get('Olcs\Service\Data\User')->setTeam($data['assignedToTeam']);
        }
        if (isset($data['assignment']['assignedToTeam'])) {
            // on POST, the data is nested
            $this->getServiceLocator()->get('Olcs\Service\Data\User')->setTeam($data['assignment']['assignedToTeam']);
        }

        $form = $this->getForm('TaskReassign')
            ->setData($this->expandData($data));

        $this->formPost($form, 'processAssignTask');

        if ($this->getResponse()->getContent() !== '') {
            return $this->getResponse();
        }

        $this->loadScripts(['forms/task']);

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('pages/form');
        $tasks = $this->params('task');

        $tasksCount = count(explode('-', $tasks));
        $formTitle = ($tasksCount == 1) ? 'Re-assign task' : 'Re-assign (' . $tasksCount . ') tasks';

        return $this->renderView($view, $formTitle);
    }

    /**
     * Close one or several tasks to a different team/user
     *
     * @return ViewModel
     */
    public function closeAction()
    {
        $form = $this->getForm('TaskClose');
        $this->formPost($form, 'processCloseTask');

        if ($this->getResponse()->getContent() !== '') {
            return $this->getResponse();
        }

        $tasks = $this->params('task');
        $tasksCount = count(explode('-', $tasks));
        if ($tasksCount > 1) {
            $form->get('details')->setLabel('tasks.close.multiple');
        }

        $this->loadScripts(['forms/task']);

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('pages/form');

        $formTitle = ($tasksCount == 1) ? 'Close task' : 'Close (' . $tasksCount . ') tasks';

        return $this->renderView($view, $formTitle);
    }

    /**
     * Callback invoked when the form is valid
     *
     * @param array $data Data
     *
     * @return \Zend\Http\Response
     */
    public function processCloseTask($data)
    {
        $ids = explode('-', $this->params('task'));

        $response = $this->handleCommand(CloseTasks::create(['ids' => $ids]));

        if ($response->isOk()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addSuccessMessage('task-close-success');
        } else {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
        }

        return $this->redirectToList();
    }

    /**
     * Callback invoked when the form is valid
     *
     * @param array $data Data
     *
     * @return \Zend\Http\Response
     */
    public function processAssignTask($data)
    {
        if (isset($data['assignment'])) {
            $dtoData = [
                'ids' => explode('-', $this->params('task')),
                'user' => $data['assignment']['assignedToUser'],
                'team' => $data['assignment']['assignedToTeam']
            ];

            $response = $this->handleCommand(ReassignTasks::create($dtoData));

            if ($response->isOk()) {
                $this->getServiceLocator()->get('Helper\FlashMessenger')->addSuccessMessage('task-reassign-success');
            } else {
                $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
            }

        }
        return $this->redirectToList();
    }

    /**
     * Set up and post form
     *
     * @param string $type Type
     *
     * @return ViewModel
     */
    private function formAction($type)
    {
        $data = $this->mapDefaultData();

        // Set up the data services so that dynamic selects populate correctly if we already have data
        if (isset($data['category'])) {
            $this->getServiceLocator()->get('Olcs\Service\Data\SubCategory')->setCategory($data['category']);
        }
        if (isset($data['assignedToTeam'])) {
            $this->getServiceLocator()->get('Olcs\Service\Data\User')->setTeam($data['assignedToTeam']);
        }

        $form = $this->getForm('Task');
        $this->getServiceLocator()->get('Helper\Form')->setFormActionFromRequest($form, $this->getRequest());

        if (isset($data['isClosed']) && $data['isClosed'] === 'Y') {
            /** @var FormHelperService $formHelper */
            $formHelper = $this->getServiceLocator()->get('Helper\Form');
            $formHelper->disableElements($form);
            $formHelper->enableElements($form->get('form-actions')->get('cancel'));
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

        if (isset($data['assignedByUser']['contactDetails']['person']['familyName'])) {
            $person = $data['assignedByUser']['contactDetails']['person'];
            $data['assignedByUserName'] = $person['forename'] . ' ' . $person['familyName'];
        } else {
            $data['assignedByUserName'] = 'Not set';
        }

        $form->setData($this->expandData($data));
        $this->formPost($form, 'process' . $type . 'Task');

        // we have to allow for the fact that our process callback has
        // already set some response data. If so, respect it and
        // bail out early
        if ($this->getResponse()->getContent() !== '') {
            return $this->getResponse();
        }

        $this->loadScripts(['forms/task']);

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('pages/form');

        return $this->renderView($view, $type . ' task');
    }

    /**
     * Callback invoked when the form is valid
     *
     * @param array $data Data
     *
     * @return void|\Zend\Http\Response
     */
    public function processAddTask($data)
    {
        $data = $this->flattenData($data);

        if ($this->isButtonPressed('cancel')) {
            return $this->redirectToList();
        }

        $response = $this->handleCommand(CreateTask::create($data));

        if ($response->isOk()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addSuccessMessage('task-create-success');
        } else {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
        }

        return $this->redirectToList();
    }

    /**
     * Callback invoked when the form is valid
     *
     * @param array $data Data
     *
     * @return void|\Zend\Http\Response
     */
    public function processEditTask($data)
    {
        $data = $this->flattenData($data);

        if ($this->isButtonPressed('cancel')) {
            return $this->redirectToList();
        }

        $response = $this->handleCommand(UpdateTask::create($data));

        if ($response->isOk()) {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addSuccessMessage('task-update-success');
        } else {
            $this->getServiceLocator()->get('Helper\FlashMessenger')->addErrorMessage('unknown-error');
        }

        return $this->redirectToList();
    }

    /**
     * Redirect back to list of tasks
     *
     * @return \Zend\Http\Response
     */
    public function redirectToList()
    {
        // always use params from route, not the task data!
        $taskType = $this->params('type');
        $taskTypeId = $this->params('typeId');
        $options = ['query' => $this->getRequest()->getQuery()->toArray()];
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
            case 'organisation':
                $route = 'operator/processing/tasks';
                $params = ['organisation' => $taskTypeId];
                break;
            default:
                // no type - call from the home page, need to redirect back after action
                $route = 'dashboard';
                $params = [];
                break;
        }
        return $this->redirect()->toRouteAjax($route, $params, $options);
    }

    /**
     * Merge some sensible default dropdown values with any POST data we may have
     *
     * @return array
     */
    private function mapDefaultData()
    {
        $user = $this->currentUser()->getUserData();

        $defaults = [
            'assignedToUser' => $user['id'],
            'assignedToTeam' => $user['team']['id']
        ];

        $taskId = $this->params('task');

        if ($taskId && strpos($taskId, '-') === false) {
            $childProperties = [
                'category',
                'subCategory',
                'assignedToTeam',
                'assignedToUser',
                'assignedDate'
            ];

            $response = $this->handleQuery(Task::create(['id' => $taskId]));
            $resource = $response->getResult();

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
     * Flatten nested fieldset data into a collapsed array
     *
     * @param array $data Data
     *
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
            $taskType = $this->params('type');
            $taskTypeId = $this->params('typeId');

            $method = 'flattenDataFor' . ucfirst($taskType);
            if (method_exists($this, $method)) {
                $data = $this->$method($data, $taskTypeId);
            }
        }

        return $data;
    }

    /**
     * Flatten data for licence
     *
     * @param array $data       Data
     * @param int   $taskTypeId Task type id
     *
     * @return array
     */
    protected function flattenDataForLicence($data, $taskTypeId)
    {
        $data['licence'] = $taskTypeId;
        return $data;
    }

    /**
     * Flatten data for application
     *
     * @param array $data       Data
     * @param int   $taskTypeId Task type id
     *
     * @return array
     */
    protected function flattenDataForApplication($data, $taskTypeId)
    {
        $data['application'] = $taskTypeId;
        $data['licence'] = $this->getLicenceIdForApplication($taskTypeId);
        return $data;
    }

    /**
     * Flatten data for TM
     *
     * @param array $data       Data
     * @param int   $taskTypeId Task type id
     *
     * @return array
     */
    protected function flattenDataForTm($data, $taskTypeId)
    {
        $data['transportManager'] = $taskTypeId;
        return $data;
    }

    /**
     * Flatten data for bus reg
     *
     * @param array $data       Data
     * @param int   $taskTypeId Task type id
     *
     * @return array
     */
    protected function flattenDataForBusreg($data, $taskTypeId)
    {
        $data['busReg'] = $taskTypeId;
        $busReg = $this->getBusReg($taskTypeId);
        $data['licence'] = $busReg['licence']['id'];
        return $data;
    }

    /**
     * Flatten data for case
     *
     * @param array $data       Data
     * @param int   $taskTypeId Task type id
     *
     * @return array
     */
    protected function flattenDataForCase($data, $taskTypeId)
    {
        $data['case'] = $taskTypeId;
        $case = $this->getCase($taskTypeId);
        if (isset($case['licence']['id'])) {
            $data['licence'] = $case['licence']['id'];
        }
        if (isset($case['transportManager']['id'])) {
            $data['transportManager'] = $case['transportManager']['id'];
        }
        return $data;
    }

    /**
     * Flatten data for organisation
     *
     * @param array $data       Data
     * @param int   $taskTypeId Task type id
     *
     * @return array
     */
    protected function flattenDataForOrganisation($data, $taskTypeId)
    {
        $data['irfoOrganisation'] = $taskTypeId;
        return $data;
    }

    /**
     * Gets the case by id
     *
     * @param integer $id Id
     *
     * @return array
     */
    protected function getCase($id)
    {
        $response = $this->handleQuery(Cases::create(['id' => $id]));
        return $response->getResult();
    }

    /**
     * Expand a flattened array of data into form fieldsets
     *
     * @param array $data Data
     *
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
     * Get task type details
     *
     * @return array
     */
    protected function getTaskTypeDetails()
    {
        $taskId = $this->params('task');

        if (empty($taskId)) {
            $taskType = $this->params('type');

            if (!$taskType) {
                throw new BadRequestException('No task id provided');
            }

            return [$taskType, $this->params('typeId'), null, null];
        }

        $taskDetails = $this->getTaskDetails($taskId);
        $taskType = strtolower($taskDetails['linkType']);

        switch ($taskType) {
            case 'transport manager':
                return ['tm', $taskDetails['linkId'], $taskDetails['linkDisplay'], null];
            case 'bus registration':
                return ['busreg', $taskDetails['linkId'], $taskDetails['linkDisplay'], null];
            case 'irfo organisation':
                return ['organisation', $taskDetails['linkId'], $taskDetails['linkDisplay'], null];
            case 'submission':
                return ['submission', $taskDetails['linkId'], $taskDetails['linkDisplay'], $taskDetails['caseId']];
            default:
                return [$taskType, $taskDetails['linkId'], $taskDetails['linkDisplay'], null];
        }
    }

    /**
     * Get link to display in add / edit form
     *
     * @return string
     */
    protected function getLinkForTaskForm()
    {
        list($taskType, $taskTypeId, $linkDisplay, $caseId) = $this->getTaskTypeDetails();

        $method = 'getLinkForTaskFormFor' . ucfirst($taskType);
        if (method_exists($this, $method)) {
            return $this->$method($taskTypeId, $linkDisplay, $caseId);
        }

        return '';
    }

    /**
     * Get link to display in add / edit form for licence
     *
     * @param int    $taskTypeId  Task type id
     * @param string $linkDisplay Text to display
     *
     * @return string
     */
    protected function getLinkForTaskFormForLicence($taskTypeId, $linkDisplay)
    {
        if (!$linkDisplay) {
            $licence = $this->getLicence($taskTypeId);
        }

        $url = $this->url()->fromRoute('lva-licence', ['licence' => $taskTypeId]);

        return sprintf('<a href="%s">%s</a>', $url, $linkDisplay ? $linkDisplay : $licence['licNo']);
    }

    /**
     * Get link to display in add / edit form for application
     *
     * @param int    $taskTypeId  Task type id
     * @param string $linkDisplay Text to display
     *
     * @return string
     */
    protected function getLinkForTaskFormForApplication($taskTypeId, $linkDisplay)
    {
        $application = $this->getApplication($taskTypeId);
        $licNo = $application['licence']['licNo'];

        $licUrl = $this->url()->fromRoute('lva-licence', ['licence' => $application['licence']['id']]);
        $appUrl = $this->url()->fromRoute('lva-application', ['application' => $taskTypeId]);

        return sprintf('<a href="%s">%s</a> / <a href="%s">%s</a>', $licUrl, $licNo, $appUrl, $taskTypeId);
    }

    /**
     * Get link to display in add / edit form for TM
     *
     * @param int    $taskTypeId  Task type id
     * @param string $linkDisplay Text to display
     *
     * @return string
     */
    protected function getLinkForTaskFormForTm($taskTypeId, $linkDisplay)
    {
        $url = $this->url()->fromRoute('transport-manager/details', ['transportManager' => $taskTypeId]);

        return $this->getLinkMarkup($url, $linkDisplay, $taskTypeId);
    }

    /**
     * Get link to display in add / edit form for bus reg
     *
     * @param int    $taskTypeId  Task type id
     * @param string $linkDisplay Text to display
     *
     * @return string
     */
    protected function getLinkForTaskFormForBusreg($taskTypeId, $linkDisplay)
    {
        $busReg = $this->getBusReg($taskTypeId);

        $params = ['busRegId' => $taskTypeId, 'licence' => $busReg['licence']['id']];
        $url = $this->url()->fromRoute('licence/bus-details', $params);

        return $this->getLinkMarkup($url, $linkDisplay, $busReg['regNo']);
    }

    /**
     * Get link to display in add / edit form for case
     *
     * @param int    $taskTypeId  Task type id
     * @param string $linkDisplay Text to display
     *
     * @return string
     */
    protected function getLinkForTaskFormForCase($taskTypeId, $linkDisplay)
    {
        $url = $this->url()->fromRoute('case', ['case' => $taskTypeId]);

        return $this->getLinkMarkup($url, $linkDisplay, $taskTypeId);
    }

    /**
     * Get link to display in add / edit form for opposition
     *
     * @param int    $taskTypeId  Task type id
     * @param string $linkDisplay Text to display
     *
     * @return string
     */
    protected function getLinkForTaskFormForOpposition($taskTypeId, $linkDisplay)
    {
        $url = $this->url()->fromRoute('case_opposition', ['case' => $taskTypeId]);

        return $this->getLinkMarkup($url, $linkDisplay, $taskTypeId);
    }

    /**
     * Get link to display in add / edit form for organisation
     *
     * @param int    $taskTypeId  Task type id
     * @param string $linkDisplay Text to display
     *
     * @return string
     */
    protected function getLinkForTaskFormForOrganisation($taskTypeId, $linkDisplay)
    {
        $url = $this->url()->fromRoute('operator/business-details', ['organisation' => $taskTypeId]);

        return $this->getLinkMarkup($url, $linkDisplay, $taskTypeId);
    }

    /**
     * Get link to display in add / edit form for submission
     *
     * @param int    $taskTypeId  Task type id
     * @param string $linkDisplay Text to display
     * @param int    $caseId      Case id
     *
     * @return string
     */
    protected function getLinkForTaskFormForSubmission($taskTypeId, $linkDisplay, $caseId)
    {
        $url = $this->url()->fromRoute(
            'submission',
            ['submission' => $taskTypeId, 'case' => $caseId, 'action' => 'details']
        );

        return $this->getLinkMarkup($url, $linkDisplay, $taskTypeId);
    }

    /**
     * Get link markup
     *
     * @param string $url         Url
     * @param string $linkDisplay Text to display
     * @param string $fallback    Default text
     *
     * @return string
     */
    protected function getLinkMarkup($url, $linkDisplay, $fallback)
    {
        return sprintf('<a href="%s">%s</a>', $url, $linkDisplay ? $linkDisplay : $fallback);
    }

    /**
     * Get licence
     *
     * @param int $id Id
     *
     * @return array
     */
    protected function getLicence($id)
    {
        return $this->handleQuery(Licence::create(['id' => $id]))->getResult();
    }

    /**
     * Get application
     *
     * @param int $id Id
     *
     * @return array
     */
    protected function getApplication($id)
    {
        return $this->handleQuery(Application::create(['id' => $id]))->getResult();
    }

    /**
     * Get licence id for application
     *
     * @param int $id Id
     *
     * @return int
     */
    protected function getLicenceIdForApplication($id)
    {
        return $this->getApplication($id)['licence']['id'];
    }
}
