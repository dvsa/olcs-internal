<?php

namespace OlcsTest\Controller;

use Common\Service\Data\Search\Search;
use Olcs\Controller\SearchController;
use Mockery as m;
use Olcs\Service\Data\Search\SearchType;
use Zend\Stdlib\ArrayObject;
use Olcs\TestHelpers\ControllerPluginManagerHelper;

/**
 * Class SearchControllerTest
 * @package OlcsTest\Controller
 */
class SearchControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ControllerPluginManagerHelper
     */
    protected $pluginManagerHelper;

    public function __construct()
    {
        $this->pluginManagerHelper = new ControllerPluginManagerHelper();
    }

    public function testIndexActionWithPostData()
    {
        $postData = ['index' => 'application', 'search' => 'asdf', 'action' => 'search'];

        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            ['params' => 'Params', 'viewHelperManager' => 'ViewHelperManager', 'url' => 'Url']
        );

        $url = $mockPluginManager->get('url');
        $url->shouldReceive('fromRoute');

        $placeholder = new \Zend\View\Helper\Placeholder();

        $mockViewHelperManager = $mockPluginManager->get('viewHelperManager', '');
        $mockViewHelperManager->shouldReceive('get')->with('placeholder')->andReturn($placeholder);

        $searchFilterForm = new \Zend\Form\Form();

        $mockParams = $mockPluginManager->get('params', '');
        $mockParams->shouldReceive('fromRoute')->with()->andReturn([]);
        //$mockParams->shouldReceive('fromPost')->with()->andReturn([]);
        $mockParams->shouldReceive('fromQuery')->with()->andReturn([]);
        $mockParams->shouldReceive('fromRoute')->with('index')->andReturn($postData['index']);
        $mockParams->shouldReceive('fromPost')->withNoArgs()->andReturn($postData);

        $mockContainer = new ArrayObject();
        $mockContainer['search'] ='testQuery';
        $mockSearchForm = m::mock('Zend\Form\Form');
        $mockSearchForm->shouldReceive('getObject')->andReturn($mockContainer);
        $mockSearchForm->shouldReceive('setData');
        $mockSearchForm->shouldReceive('isValid')->andReturn(true);
        $mockSearchForm->shouldReceive('getData')->andReturn($postData);

        $mockSearch = m::mock(Search::class);
        $mockSearch->shouldReceive('setQuery')->andReturnSelf();
        $mockSearch->shouldReceive('setRequest')->andReturnSelf();
        $mockSearch->shouldReceive('setIndex')->with($postData['index'])->andReturnSelf();
        $mockSearch->shouldReceive('setSearch')->with('testQuery')->andReturnSelf();
        $mockSearch->shouldReceive('fetchResultsTable')->andReturn('resultsTable');

        $mockSearchType = m::mock(SearchType::class);
        $mockSearchType->shouldReceive('getNavigation')->andReturn('navigation');

        $mockSl = m::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockSl->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockSl->shouldReceive('get')->with(Search::class)->andReturn($mockSearch);
        $mockSl->shouldReceive('get')->with(SearchType::class)->andReturn($mockSearchType);
        $mockSl->shouldReceive('get')->with('viewHelperManager')->andReturn($mockViewHelperManager);

        $mockRouteMatch = m::mock('Zend\Mvc\Router\RouteMatch');
        $mockRouteMatch->shouldReceive('setParam')->with('index', 'application');

        $placeholder->getContainer('headerSearch')->set($mockSearchForm);
        $placeholder->getContainer('searchFilter')->set($searchFilterForm);

        $sut = new SearchController();
        $sut->setPluginManager($mockPluginManager);
        $sut->setServiceLocator($mockSl);
        $sut->getRequest()->setMethod('POST');
        $sut->getEvent()->setRouteMatch($mockRouteMatch);

        $view = $sut->searchAction()->getChildren()[1]->getVariables();

        $this->assertEquals('navigation', $view->indexes);
        $this->assertEquals('resultsTable', $view->results);
    }
}
