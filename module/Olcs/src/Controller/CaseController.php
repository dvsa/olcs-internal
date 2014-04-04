<?php

/**
 * Case Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */

namespace Olcs\Controller;

use Common\Controller\FormActionController;
use Zend\View\Model\ViewModel;

/**
 * Case Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class CaseController extends FormActionController
{

    public function notFoundAction()
    {
        $view = new ViewModel();

        $caseId = $this->params()->fromRoute('case');
        $action = $this->params()->fromRoute('action');

        $pm = $this->getPluginManager();

        $tabs = $this->getTabInformationArray();

        if (!array_key_exists($action, $tabs)) {
            return parent::notFoundAction();
        }

        $case = $this->getCase($caseId);

        $summary = $this->getCaseSummaryArray($case);
        $details = $this->getCaseDetailsArray($case);

        $view->setVariables(
            ['case' => $case, 'tabs' => $tabs, 'tab' => $action, 'summary' => $summary, 'details' => $details]
        );

        $view->setTemplate('case/manage');
        return $view;
    }

    public function getCase($caseId)
    {
        $case = $this->makeRestCall('VosaCase', 'GET', array('id' => $caseId));
        $licence = $this->makeRestCall('Licence', 'GET', array('id' => $case['licence']));

        $case['licenceId'] = $case['licence'];
        $case['licence'] = $licence;

        $ta = $this->makeRestCall('TrafficArea', 'GET', array('id' => $case['licence']['trafficArea']));
        $case['licence']['trafficArea'] = $ta;

        $org = $this->makeRestCall('Organisation', 'GET', array('id' => $case['licence']['organisation']));
        $case['licence']['organisation'] = $org;

        return $case;
    }

    /**
     * Returns tab information as an array.
     *
     * @return array
     */
    public function getTabInformationArray()
    {
        $pm = $this->getPluginManager();

        $tabs = [
            'overview' => [
                'key' => 'overview',
                'label' => 'Overview',
                'url' => $pm->get('url')->fromRoute(null, ['action' => 'overview'], [], true),
            ],
            'convictions' => [
                'key' => 'convictions',
                'label' => 'Convictions',
                'url' => $pm->get('url')->fromRoute(null, ['action' => 'convictions'], [], true),
            ],
            'prohibitions' => [
                'key' => 'prohibitions',
                'label' => 'Prohibitions',
                'url' => $pm->get('url')->fromRoute(null, ['action' => 'prohibitions'], [], true),
            ],
        ];

        return $tabs;
    }

    public function getCaseSummaryArray(array $case)
    {
        $pm = $this->getPluginManager();

        $smmary = [

            'case_number' => [
                'label' => 'Case number',
                'value' => $case['caseNumber'],
                'url' => '',
            ],
            'operator_name' => [
                'label' => 'Operator name',
                'value' => $case['licence']['organisation']['name'],
                'url' => ''
            ],
            'licence_number' => [
                'label' => 'Licence number',
                'value' => $case['licence']['licenceNumber'],
                'url' => ''
            ],
            'ecms' => [
                'label' => 'ECMS',
                'value' => $case['ecms'],
                'url' => ''
            ],
            'categories' => [
                'label' => 'Categories',
                'value' => implode(', ', $case['categories']),
                'url' => ''
            ],
            'summary' => [
                'label' => 'Summary',
                'value' => $case['description'],
                'url' => ''
            ],
        ];

        return $smmary;
    }

    public function getCaseDetailsArray(array $case)
    {
        $pm = $this->getPluginManager();

        $opentimeDate = date('d/m/Y', strtotime($case['openTime']['date']));
        $licenceStartDate = date('d/m/Y', strtotime($case['licence']['startDate']['date']));

        $details = [

            'open_date' => [
                'label' => 'Open date',
                'value' => $opentimeDate,
                'url' => '',
            ],
            'traffic_area' => [
                'label' => 'Traffic area',
                'value' => $case['licence']['trafficArea']['areaName'],
                'url' => '',
            ],
            'status' => [
                'label' => 'Status',
                'value' => ucfirst($case['status']),
                'url' => '',
            ],
            'entity_type' => [
                'label' => 'Entity type',
                'value' => $case['licence']['organisation']['organisationType'],
                'url' => '',
            ],
            'licence_start_date' => [
                'label' => 'Licence start date',
                'value' => $licenceStartDate,
                'url' => '',
            ],
            'licence_type' => [
                'label' => 'Licence type',
                'value' => $case['licence']['licenceType'],
                'url' => '',
            ],
            'licence_category' => [
                'label' => 'Licence category',
                'value' => $case['licence']['goodsOrPsv'],
                'url' => '',
            ],
            'licence_status' => [
                'label' => 'Licence status',
                'value' => $case['licence']['licenceStatus'],
                'url' => '',
            ],
        ];

        return $details;
    }

    /**
     * List of cases if we have a licence
     *
     * @todo Handle 404
     */
    public function indexAction()
    {
        $licence = $this->params()->fromRoute('licence');

        if (empty($licence)) {

            return $this->notFoundAction();
        }

        $action = $this->params()->fromPost('action');

        if (!empty($action)) {

            $action = strtolower($action);

            $url = '/case/' . $licence . '/' . $action;

            if ($action !== 'add') {

                $id = $this->params()->fromPost('id');

                if (empty($id)) {
                    // TODO: Should add flash message here
                    die('Select an id');
                } else {
                    $this->redirect()->toUrl($url . '/' . $id);
                }

            } else {

                $this->redirect()->toUrl($url);
            }
        }

        $results = $this->makeRestCall('VosaCase', 'GET', array('licence' => $licence));

        $data['url'] = $this->getPluginManager()->get('url');

        $table = $this->getServiceLocator()->get('Table')->buildTable('case', $results, $data);

        $view = new ViewModel(['licence' => $licence, 'table' => $table]);
        $view->setTemplate('case-list');
        return $view;
    }

    /**
     * Add a new case to a licence
     *
     * @todo Handle 404 and Bad Request
     *
     * @return ViewModel
     */
    public function addAction()
    {
        $licence = $this->params()->fromRoute('licence');

        if (empty($licence)) {
            die('Bad request');
        }

        $results = $this->makeRestCall('Licence', 'GET', array('id' => $licence));

        if (empty($results)) {
            return $this->notFoundAction();
        }

        $form = $this->generateFormWithData(
            'case', 'processAddCase', array(
            'licence' => $licence
            )
        );

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('case/add');
        return $view;
    }

    /**
     * Edit a new case
     *
     * @todo Handle 404
     *
     * @return ViewModel
     */
    public function editAction()
    {
        $licence = $this->params()->fromRoute('licence');
        $case = $this->params()->fromRoute('case');

        $result = $this->makeRestCall('VosaCase', 'GET', array('id' => $case, 'licence' => $licence));

        if (empty($result)) {
            return $this->notFoundAction();
        }

        $categories = $result['categories'];
        unset($result['categories']);

        $result['fields'] = $result;

        $result['categories'] = $this->unFormatCategories($categories);

        $form = $this->generateFormWithData(
            'case', 'processEditCase', $result
        );

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('case/edit');
        return $view;
    }

    public function deleteAction()
    {
        $licence = $this->params()->fromRoute('licence');
        $case = $this->params()->fromRoute('case');

        $result = $this->makeRestCall('VosaCase', 'GET', array('id' => $case, 'licence' => $licence));

        if (empty($result)) {
            return $this->notFoundAction();
        }

        $this->makeRestCall('VosaCase', 'DELETE', array('id' => $case));

        $this->redirect()->toUrl('/case/' . $licence);
    }

    /**
     * Process adding the case
     *
     * @todo Additional fields are required for persisting - Find out where these fields come from
     * @todo Decide where to send the user afterwards
     *
     * @param type $data
     */
    protected function processAddCase($data)
    {
        // Additional fields (Mocked for now)
        $data['caseNumber'] = 12345678;
        $data['openTime'] = date('Y-m-d H:i:s');
        $data['owner'] = 7;

        $data['categories'] = $this->formatCategories($data['categories']);
        $data = array_merge($data, $data['fields']);

        $result = $this->processAdd($data, 'VosaCase');

        if (isset($result['id'])) {
            $this->redirect()->toUrl('/case/' . $data['licence']);
        }
    }

    /**
     * Process updating the case
     *
     * @todo Decide what to do on success
     *
     * @param type $data
     */
    protected function processEditCase($data)
    {
        $data['categories'] = $this->formatCategories($data['categories']);
        $data = array_merge($data, $data['fields']);

        $result = $this->processEdit($data, 'VosaCase');

        $this->redirect()->toUrl('/case/' . $data['licence']);
    }

    /**
     * Format categories into a single dimension array
     *
     * @param array $categories
     * @return array
     */
    private function formatCategories($categories = array())
    {
        $return = array();

        foreach ($categories as $array) {

            foreach ($array as $category) {

                $return[] = str_replace('case_category.', '', $category);
            }
        }

        return $return;
    }

    /**
     * Format the categories from the REST response into the form's format
     *
     * @todo Look at re-factoring this
     *
     * @param array $categories
     * @return array
     */
    private function unFormatCategories($categories = array())
    {
        $config = $this->getServiceLocator()->get('Config');

        $formattedCategories = array();

        $translations = array();

        foreach ($config['static-list-data'] as $key => $array) {

            if (preg_match('/case_categories_([a-z]+)/', $key, $matches)) {

                foreach (array_keys($array) as $id) {

                    $translations[str_replace('case_category.', '', $id)] = $matches[1];
                }
            }
        }

        foreach ($categories as $categoryId) {

            if (!isset($formattedCategories[$translations[$categoryId]])) {
                $formattedCategories[$translations[$categoryId]] = array();
            }

            $formattedCategories[$translations[$categoryId]][] = 'case_category.' . $categoryId;
        }

        return $formattedCategories;
    }

}
