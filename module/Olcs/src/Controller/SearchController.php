<?php

/**
 * Search controller
 * Search for operators and licences
 *
 * @author Mike Cooper <michael.cooper@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller;

use Common\Service\Data\Search\Search;
use Olcs\Service\Data\Search\SearchType;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

/**
 * Main search controller
 *
 * @author Mike Cooper <michael.cooper@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class SearchController extends AbstractController
{
    private $sd = [];

    protected $navigationId = 'mainsearch';

    public function getSearchData()
    {
        $container = new Container('global_search');

        $remove = [
            'controller',
            'action',
            'module',
            'submit'
        ];

        $incomingParameters = [];

        if ($routeParams = $this->params()->fromRoute()) {
            $incomingParameters += $routeParams;
        }

        if ($queryParams = (array)$this->params()->fromQuery()) {
            $incomingParameters = array_merge($incomingParameters, $queryParams);
        }

        if ($postParams = (array)$this->params()->fromPost()) {
            $incomingParameters = array_merge($incomingParameters, $postParams);
        }

        /**
         * Now remove all the data we don't want in the query string.
         */
        $incomingParameters = array_diff_key($incomingParameters, array_flip($remove));

        $incomingParameters = array_merge($container->getArrayCopy(), $incomingParameters);

        $container->exchangeArray($incomingParameters);

        return $container->getArrayCopy();
    }

    /**
     * At first glance this seems a little unnecessary, but we need to intercept the post
     * and turn it into a get. This way the search URL contains the search params.
     */
    public function postAction()
    {
        $sd = $this->getSearchData();

        /**
         * Remove the "index" key from the incoming parameters.
         */
        $index = $sd['index'];
        unset($sd['index']);

        return $this->redirect()->toRoute(
            'search',
            ['index' => $index, 'action' => 'search'],
            ['query' => $sd, 'code' => 303],
            true
        );
    }

    public function backAction()
    {
        $sd = $this->getSearchData();

        /**
         * Remove the "index" key from the incoming parameters.
         */
        $index = $sd['index'];
        unset($sd['index']);

        return $this->redirect()->toRoute(
            'search',
            ['index' => $index, 'action' => 'search'],
            ['query' => $sd, 'code' => 303],
            true
        );
    }

    public function indexAction()
    {
        return $this->backAction();
    }

    public function processSearchData()
    {
        // Crazy race condition means that we need to "build" the form here!
        /** @var \Olcs\Service\Data\Search\Search $searchService **/
        $searchService = $this->getServiceLocator()->get('DataServiceManager')->get(Search::class);
        //$searchService->fetchFiltersForm();

        $incomingParameters = [];

        if ($routeParams = $this->params()->fromRoute()) {
            $incomingParameters += $routeParams;
        }

        if ($postParams = $this->params()->fromPost()) {
            $incomingParameters += $postParams;
        }

        if ($queryParams = (array)$this->getRequest()->getQuery()) {
            $incomingParameters = array_merge($incomingParameters, $queryParams);
        }

        //there are multiple places search data can come from:
        //route, query, post and session

        //there are lots of params we are interested in:
        //filters, index, search query, page, limit

        //a post request can come from two forms a) the filter form, b) the query form
        $form = $this->getSearchForm();
        $form->setData($incomingParameters);

        if ($form->isValid()) {
            //save to session, reset filters in session...
            //get index from post as well, override what is in the route match
            $data = $form->getData();
            $this->getEvent()->getRouteMatch()->setParam('index', $data['index']);
        }
    }

    /**
     * Returns the header search form.
     *
     * @return \Olcs\Form\Model\Form\HeaderSearch
     */
    private function getSearchForm()
    {
        $form = $this->getViewHelperManager()
            ->get('placeholder')
            ->getContainer('headerSearch')
            ->getValue();

        return $form;
    }

    /**
     * Returns the search filter form.
     *
     * @return \Olcs\Form\Model\Form\SearchFilter
     */
    public function getFiltersForm()
    {
        /** @var \Zend\Form\Form $form */
        $form = $this->getViewHelperManager()
            ->get('placeholder')
            ->getContainer('searchFilter')
            ->getValue();

        $sd = $this->getSearchData();

        $url = $this->url()->fromRoute(
            'search',
            ['index' => $sd['index'], 'action' => 'post'],
            ['query' => ['search' => $sd['search']]]
        );

        $form->setAttribute('action', $url);
        $form->setData($sd);

        return $form;
    }

    public function searchAction()
    {
        $sd = $this->getSearchData();

        /**
         * This might
         */
        $this->getFiltersForm();
        $data = $this->getSearchForm()->getObject();
        //override with get route index unless request is post
        //if ($this->getRequest()->isPost()) {
            $this->processSearchData();
        //}

        //update data with information from route, and rebind to form so that form data is correct
        $data['index'] = $this->params()->fromRoute('index');
        $this->getSearchForm()->setData($data);

        if (empty($data['search'])) {
            $this->flashMessenger()->addErrorMessage('Please provide a search term');
            return $this->redirectToRoute('dashboard');
        }

        /** @var Search $searchService **/
        $searchService = $this->getServiceLocator()->get('DataServiceManager')->get(Search::class);

        $searchService->setQuery($this->getRequest()->getQuery())
                      ->setRequest($this->getRequest())
                      ->setIndex($data['index'])
                      ->setSearch($data['search']);

        $view = new ViewModel();

        /** @var SearchType $searchService **/
        $searchTypeService = $this->getServiceLocator()->get('DataServiceManager')->get(SearchType::class);

        $view->indexes = $searchTypeService->getNavigation('internal-search', ['search' => $sd['search']]);
        $view->results = $searchService->fetchResultsTable();

        $view->setTemplate('layout/main-search-results');

        return $this->renderView($view, 'Search results');
    }

    /**
     * Search form action
     *
     * @return ViewModel
     */
    public function advancedAction()
    {
        // Below is for setting route params for the breadcrumb
        $this->setBreadcrumb(array('search' => array()));
        $form = $this->generateFormWithData('search', 'processSearch');

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('partials/form');

        return $this->renderView($view, 'Search', 'Search for licences using any of the following fields');
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
            'forename',
            'familyName',
            'birthDate',
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

        /**
         * @NOTE (RC) added data to query string rather than route params as data contained a nested array which was
         * causing an error in zf2 url builder. I am informed by (CR) that this advanced search is disappearing soon
         * anyway
         */
        $url = $this->url()->fromRoute('operators/operators-params', [], array('query' => $data));

        $this->redirect()->toUrl($url);
    }

    /**
     * Operator search results
     *
     * @return ViewModel
     */
    public function operatorAction()
    {
        $postData = (array)$this->getRequest()->getPost();
        if (isset($postData['action']) && $postData['action'] == 'Create operator') {
            return $this->redirectToRoute('create_operator');
        }
        if (isset($postData['action']) && $postData['action'] == 'Create transport manager') {
            return $this->redirectToRoute('create_transport_manager');
        }
        $data = $this->params()->fromRoute();
        $results = $this->makeRestCall('OperatorSearch', 'GET', $data);

        $config = $this->getServiceLocator()->get('Config');
        $static = $config['static-list-data'];

        foreach ($results['Results'] as $key => $result) {

            $orgType = $result['organisation_type'];

            if (isset($static['business_types'][$orgType])) {
                $results['Results'][$key]['organisation_type'] = $static['business_types'][$orgType];
            }
        }

        $table = $this->getTable('operator', $results, $data);

        $view = new ViewModel(['table' => $table]);
        $view->setTemplate('partials/table');
        return $this->renderView($view, 'Search results');
    }

    /**
     * Sets the navigation to that secified in the controller. Useful for when a controller is
     * 100% reresented by a single navigation object.
     *
     * @see $this->navigationId
     *
     * @return boolean true
     */
    public function setNavigationCurrentLocation()
    {
        $navigation = $this->getServiceLocator()->get('Navigation');
        if (!empty($this->navigationId)) {
            $navigation->findOneBy('id', $this->navigationId)->setActive();
        }

        return true;
    }
}
