<?php

/**
 * Search controller
 *
 * Search for operators and licences
 *
 * @package    olcs
 * @author     Mike Cooper
 * @author     Rob Caiger <rob@clocal.co.uk>
 */

namespace Olcs\Controller;

use Common\Controller\FormActionController;
use Zend\View\Model\ViewModel;

/**
 * Search controller
 *
 * Search for operators and licences
 *
 * @package    olcs
 * @author     Mike Cooper
 * @author     Rob Caiger <rob@clocal.co.uk>
 */
class SearchController extends FormActionController
{

    /**
     * Search form action
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        // Below is for setting route params for the breadcrumb
        $this->setBreadcrumb(array('search' => array()));
        $navigation = $this->getServiceLocator()->get('navigation');
        $form = $this->generateFormWithData('search', 'processSearch');

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('search/index');
        return $view;
    }

    /**
     * Process the search
     *
     * @param array $data
     */
    public function processSearch($data)
    {
        $data = array_merge($data['search'], $data['search-advanced']);
        $personSearch = array(
            'firstName',
            'lastName',
            'dateOfBirth',
            'transportManagerId'
        );

        $searchType = 'operators';

        foreach ($data as $key => $value) {

            if (empty($value)) {
                unset($data[$key]);
            } elseif (in_array($key, $personSearch)) {
                $searchType = 'person';
            }
        }
        $url = $this->url()->fromRoute('operators/operators-params', $data);

        $this->redirect()->toUrl($url);
    }

    /**
     * Person search results: NOT IMPLEMENTED YET
     *
     * @todo Implement person search results
     *
     * @return ViewModel
     */
    /*public function personAction()
    {
        die('Person search is out of scope');
        $data = $this->params()->fromQuery();

        $results = $this->makeRestCall('PersonSearch', 'GET', $data);

        $view = new ViewModel(['results' => $results]);
        $view->setTemplate('results');
        return $view;
    }*/

    /**
     * Operator search results
     *
     * @return ViewModel
     */
    public function operatorAction()
    {
        $data = $this->params()->fromRoute();
        $results = $this->makeRestCall('OperatorSearch', 'GET', $data);

        $config = $this->getServiceLocator()->get('Config');
        $static = $config['static-list-data'];

        foreach ($results['Results'] as $key => $result) {
            if (isset($static['business_types'][$result['organisation_type']])) {
                $results['Results'][$key]['organisation_type'] = $static['business_types'][$result['organisation_type']];
            }
        }

        $data['url'] = $this->url();
        $table = $this->getServiceLocator()->get('Table')->buildTable('operator', $results, $data);

        $view = new ViewModel(['table' => $table]);
        $view->setTemplate('results-operator');
        return $view;
    }
}
