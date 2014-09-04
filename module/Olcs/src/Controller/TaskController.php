<?php

/**
 * Task Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */

namespace Olcs\Controller;

use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Olcs\Controller\Traits;

/**
 * Task Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
class TaskController extends AbstractController
{
    use Traits\LicenceControllerTrait;

    /**
     * Add a new task
     *
     * @return ViewModel
     */
    public function addAction()
    {
        return $this->formAction('Add');
    }

    public function editAction()
    {
        return $this->formAction('Edit');
    }

    private function formAction($type)
    {
        $data = $this->mapDefaultData();

        $filters = $this->mapFilters($data);

        $form = $this->getForm('task');

        $selects = array(
            'details' => array(
                'category' => $this->getListData('Category', [], 'description'),
                'taskSubCategory' => $this->getListData('TaskSubCategory', $filters)
            ),
            'assignment' => array(
                'assignedToTeam' => $this->getListData('Team'),
                'assignedToUser' => $this->getListData('User', $filters, 'name', 'id', 'Unassigned')
            )
        );

        foreach ($selects as $fieldset => $inputs) {
            foreach ($inputs as $name => $options) {
                $form->get($fieldset)
                    ->get($name)
                    ->setValueOptions($options);
            }
        }

        $licence = $this->getLicence();

        $url = sprintf(
            '<a href="%s">%s</a>',
            $this->url()->fromRoute(
                'licence/details/overview',
                array(
                    'licence' => $this->getFromRoute('licence')
                )
            ),
            $licence['licNo']
        );

        if (isset($data['isClosed']) && $data['isClosed'] === 'Y') {
            $this->disableFormElements($form);
            $textStatus = 'Closed';
        } else {
            $textStatus = 'Open';
        }

        $details = $form->get('details');

        $details->get('link')->setValue($url);
        $details->get('status')->setValue('<b>' . $textStatus . '</b>');

        $form->setData($this->expandData($data));

        $this->formPost($form, 'process' . $type . 'Task');

        // we have to allow for the fact that our process callback has
        // already set some response data. If so, respect it and
        // bail out early
        if ($this->getResponse()->getContent() !== "") {
            return $this->getResponse();
        }

        $view = new ViewModel(
            [
                'form' => $form,
                'inlineScript' => $this->loadScripts(['task-form'])
            ]
        );

        $view->setTemplate('task/add-or-edit');
        return $this->renderView($view, $type . ' task');
    }

    /**
     * Override the parent getListData method simply to save us constantly having to
     * supply the $showAll parameter as 'Please select'
     */
    protected function getListData($entity, $data = array(), $titleKey = 'name', $primaryKey = 'id', $showAll = 'Please select')
    {
        return parent::getListData($entity, $data, $titleKey, $primaryKey, $showAll);
    }

    /**
     * Callback invoked when the form is valid
     */
    public function processAddTask($data)
    {
        return $this->processForm($data, 'Add');
    }

    public function processEditTask($data)
    {
        return $this->processForm($data, 'Edit');
    }

    private function processForm($data, $type)
    {
        $licence = $this->getFromRoute('licence');

        $data = $this->flattenData($data);

        $method = 'process' . $type;

        $result = $this->$method($data, 'Task');

        if ($type === 'Edit' || isset($result['id'])) {
            $route = 'licence/processing';
            $params = ['licence' => $licence];

            // @NOTE: at some point we'll probably want to abstract this behind a
            // redirect helper, such that *all* redirects either set a location
            // header or return JSON based on the request type. That way it can
            // be totally transparent in concrete controllers like this one.
            if ($this->getRequest()->isXmlHttpRequest()) {
                $data = [
                    'status' => 302,
                    'location' => $this->url()->fromRoute($route, $params)
                ];

                $this->getResponse()->getHeaders()->addHeaders(
                    ['Content-Type' => 'application/json']
                );
                $this->getResponse()->setContent(Json::encode($data));
                return;
            }

            // bog standard redirect
            $this->redirect()->toRoute($route, $params);
        }
    }

    /**
     * Merge some sensible default dropdown values
     * with any POST data we may have
     */
    private function mapDefaultData()
    {
        $defaults = [
            'assignedToUser' => $this->getLoggedInUser(),
            'assignedToTeam' => 2 // @NOTE: not stubbed yet
        ];

        $taskId = $this->params()->fromRoute('task');
        if ($taskId) {
            $childProperties = [
                'category', 'taskSubCategory',
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
     * Map some flattened data into relevant dropdown
     * filters
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

        return $filters;
    }

    /**
     * Flatten nested fieldset data into a collapsed
     * array
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

        $data['licence'] = $this->getFromRoute('licence');
        if (isset($data['urgent'])) {
            $data['urgent'] = $data['urgent'] == '1' ? 'Y' : 'N';
        }

        return $data;
    }

    /**
     * Expand a flattened array of data into form fieldsets
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

    private function disableFormElements($element) {
        if ($element instanceof \Zend\Form\Fieldset) {
            foreach ($element->getFieldsets() as $child) {
                $this->disableFormElements($child);
            }

            foreach ($element->getElements() as $child) {
                $this->disableFormElements($child);
            }
        }

        if ($element instanceof \Zend\Form\Element\DateSelect) {
            $this->disableFormElements($element->getDayElement());
            $this->disableFormElements($element->getMonthElement());
            $this->disableFormElements($element->getYearElement());
        }

        $element->setAttribute('disabled', 'disabled');
    }
}
