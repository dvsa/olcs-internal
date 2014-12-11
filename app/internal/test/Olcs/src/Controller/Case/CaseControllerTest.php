<?php

/**
 * CaseController Test
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */

namespace OlcsTest\Controller;

use Olcs\Controller\Cases\CaseController;
use Mockery as m;
use Olcs\TestHelpers\ControllerRouteMatchHelper;
use Olcs\TestHelpers\ControllerAddEditHelper;
use Olcs\TestHelpers\ControllerPluginManagerHelper;

/**
 * CaseController Test
 */
class CaseControllerTest extends ControllerTestAbstract
{
    use \OlcsTest\Traits\MockeryTestCaseTrait;

    protected $testClass = 'Olcs\Controller\Cases\CaseController';

    protected $proxyMethdods = [
        'redirectAction' => 'redirectToRoute',
        'indexAction' => 'redirectToRoute'
    ];

    public function testGetCase()
    {
        $caseId = 29;
        $case = ['id' => 29];

        $mockService = m::mock('Olcs\Service\Data\Cases');
        $mockService->shouldReceive('fetchCaseData')->with($caseId)->andReturn($case);

        $mockSl = m::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockSl->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockSl->shouldReceive('get')->with('Olcs\Service\Data\Cases')->andReturn($mockService);

        $sut = $this->getSut();
        $sut->setServiceLocator($mockSl);

        $this->assertEquals($case, $sut->getCase($caseId));
    }

    public function testGetCaseWithId()
    {
        $caseId = 29;
        $case = ['id' => 29];

        $sut = $this->getSut();

        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(['params' => 'Params']);
        $mockParams = $mockPluginManager->get('params', '');
        $mockParams->shouldReceive('fromRoute')->with('case')->andReturn($caseId);

        $mockService = m::mock('Olcs\Service\Data\Cases');
        $mockService->shouldReceive('fetchCaseData')->with($caseId)->andReturn($case);

        $mockSl = m::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockSl->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockSl->shouldReceive('get')->with('Olcs\Service\Data\Cases')->andReturn($mockService);

        $sut->setServiceLocator($mockSl);
        $sut->setPluginManager($mockPluginManager);

        $this->assertEquals($case, $sut->getCase());
    }

    /**
     * Tests the redirectToIndex method
     */
    public function testRedirectToIndex()
    {
        $sut = $this->getSut();

        $caseId = 28;

        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            ['params' => 'Params', 'redirect' => 'Redirect']
        );

        $mockParams = $mockPluginManager->get('params', '');
        $mockParams->shouldReceive('fromQuery')->with('case', null)->andReturn($caseId);

        $mockRedirect = $mockPluginManager->get('redirect', '');
        $mockRedirect->shouldReceive('toRoute')->with(
            'case',
            ['action' => 'details', $sut->getIdentifierName() => $caseId],
            ['code' => '303'], true
        )->andReturn('redirectResponse');

        $mockPluginManager->shouldReceive('get')->with('redirect')->andReturn($mockRedirect);

        $sut->setPluginManager($mockPluginManager);

        $this->assertEquals('redirectResponse', $sut->redirectToIndex());
    }

    /**
     * Tests redirectToIndex throws the correct exception if there isn't a case id
     *
     * @expectedException \LogicException
     */
    public function testRedirectToIndexThrowsException()
    {
        $sut = $this->getMock('Olcs\Controller\Cases\CaseController', ['getQueryOrRouteParam']);
        $sut->expects($this->once())->method('getQueryOrRouteParam')->will($this->returnValue(0));

        $sut->redirectToIndex();
    }

    /**
     * Tests processSave
     *
     * @dataProvider processSaveProvider
     */
    public function testProcessSave($data)
    {
        $sut = $this->getSut();

        $caseId = 28;

        //the rest call to be returned.
        //Note this doesn't test what an actual rest call would return but it does allow us to make sure the data
        //we're passing in is correct

        $restResult = [
            'id' => $caseId,
        ];

        $mockDataService = m::mock('Common\Service\Helper\DataHelperService');
        $mockDataService->shouldReceive('processDataMap')->andReturn([]);

        $mockRestHelper = m::mock('RestHelper');
        $mockRestHelper->shouldReceive('makeRestCall')->withAnyArgs()->andReturn($restResult);

        $mockServiceManager = m::mock('\Zend\ServiceManager\ServiceManager');
        $mockServiceManager->shouldReceive('get')->with('Helper\Data')->andReturn($mockDataService);
        $mockServiceManager->shouldReceive('get')->with('Helper\Rest')->andReturn($mockRestHelper);
        //$mockServiceManager->shouldReceive('getHelperService')->with('DataHelper')->andReturn($mockDataService);
        //$mockServiceManager->shouldReceive('get->getHelperService')->with('RestService')->andReturn($mockRestHelper);

        $sut->setServiceLocator($mockServiceManager);

        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'params' => 'Params',
                'redirect' => 'Redirect',
                'FlashMessenger' => 'FlashMessenger'
            ]
        );

        $mockFlashMessenger = $mockPluginManager->get('FlashMessenger', '');
        $mockFlashMessenger->shouldReceive('addSuccessMessage');

        $mockParams = $mockPluginManager->get('params', '');
        $mockParams->shouldReceive('fromQuery')->with('case', null)->andReturn($caseId);

        $mockRedirect = $mockPluginManager->get('redirect', '');
        $mockRedirect->shouldReceive('toRoute')->with(
            'case',
            ['action' => 'details', $sut->getIdentifierName() => $caseId],
            ['code' => '303'], true
        )->andReturn('redirectResponse');

        $mockPluginManager->shouldReceive('get')->with('redirect')->andReturn($mockRedirect);

        $sut->setPluginManager($mockPluginManager);

        $this->assertEquals('redirectResponse', $sut->processSave($data));
    }

    public function processSaveProvider()
    {
        return [
            [['fields' => ['id' => '']]],
            [['fields' => ['id' => 28]]]
        ];
    }

    /**
     * Tests the edit action correctly passed the amended page layouts
     */
    public function testEditAction()
    {
        $caseId = 28;
        $licence = 7;
        $mockResult = ['id' => $caseId];
        $pageLayout = 'case';
        $pageLayoutInner = null;
        $action = 'edit';

        $sut = $this->getSut();
        $sut->setPageLayout($pageLayout);
        $sut->setPageLayoutInner($pageLayoutInner);

        $addEditHelper = new ControllerAddEditHelper();

        $mockPluginManager = $addEditHelper->getPluginManager(
            $action,
            $caseId,
            $licence,
            $sut->getIdentifierName(),
            $caseId
        );

        $sut->setPluginManager($mockPluginManager);

        $mockServiceManager = $addEditHelper->getServiceManager($action, $mockResult, 'cases');
        $sut->setServiceLocator($mockServiceManager);

        $view = $sut->editAction();
        $this->createAddEditAssertions('layout/' . $pageLayout, $view, $addEditHelper, $mockServiceManager);
    }

    /**
     * Tests the add action correctly passed the amended page layouts
     */
    public function testAddAction()
    {
        $caseId = 28;
        $licence = 7;
        $mockResult = [];
        $pageLayout = 'simple';
        $pageLayoutInner = null;
        $action = 'add';

        $sut = $this->getSut();
        $sut->setPageLayout($pageLayout);
        $sut->setPageLayoutInner($pageLayoutInner);

        $addEditHelper = new ControllerAddEditHelper();

        $mockPluginManager = $addEditHelper->getPluginManager(
            $action,
            $caseId,
            $licence,
            $sut->getIdentifierName(),
            $caseId
        );

        $sut->setPluginManager($mockPluginManager);

        $mockServiceManager = $addEditHelper->getServiceManager($action, $mockResult, 'cases');
        $sut->setServiceLocator($mockServiceManager);

        $event = $this->routeMatchHelper->getMockRouteMatch(array('action' => 'not-found'));
        $sut->setEvent($event);

        $view = $sut->addAction();
        $this->createAddEditAssertions('layout/' . $pageLayout, $view, $addEditHelper, $mockServiceManager);
    }

    public function documentsActionProvider()
    {
        return [
            [7],
            [null] // tests if licence id is ommitted from url
        ];
    }
    /**
     * Tests the document list action
     *
     * @dataProvider documentsActionProvider
     */
    public function testDocumentsAction($licenceId)
    {
        $sut = $this->getSut();

        $caseId = 28;

        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            ['params' => 'Params', 'url' => 'Url']
        );
        $mockParams = $mockPluginManager->get('params', '');
        $mockParams->shouldReceive('fromQuery')->with('case', m::any())->andReturn($caseId);
        $mockParams->shouldReceive('fromRoute')->with('case')->andReturn($caseId);
        $mockParams->shouldReceive('fromQuery')->with('licence', m::any())->andReturn($licenceId);
        $mockParams->shouldReceive('fromRoute')->with('licence', m::any())->andReturn($licenceId);
        $sut->setPluginManager($mockPluginManager);

        // We can mock/stub all the service calls that generate the table and
        // form content, this is all in the DocumentSearchTrait that is well
        // tested elsewhere
        ////////////////////////////////////////////////////////////////////////

        $sm = \OlcsTest\Bootstrap::getServiceManager();

        $tableServiceMock = m::mock('\Common\Service\Table\TableBuilder')
            ->shouldReceive('buildTable')
            ->andReturnSelf()
            ->shouldReceive('render')
            ->getMock();
        $sm->setService('Table', $tableServiceMock);

        $scriptHelperMock = m::mock('\Common\Service\Script\ScriptFactory')
            ->shouldReceive('loadFiles')
            ->with(['documents', 'table-actions'])
            ->getMock();
        $sm->setService('Script', $scriptHelperMock);

        $sm->setService('Helper\Rest', $this->getMockRestHelperForDocuments());

        $dsm = m::mock('\StdClass')
            ->shouldReceive('get')
            ->with('Olcs\Service\Data\Cases')
            ->andReturn(
                m::mock('Olcs\Service\Data\Cases')
                    ->shouldReceive('fetchCaseData')
                    ->with($caseId)
                    ->andReturn(
                        [
                            'id' => $caseId,
                            'licence' => [
                                'id' => 7
                            ]
                        ]
                    )
                    ->getMock()
            )
            ->getMock();
        $sm->setService('DataServiceManager', $dsm);

        $sut->setServiceLocator($sm);
        ////////////////////////////////////////////////////////////////////////

        $view = $sut->documentsAction();

        $this->assertInstanceOf('\Zend\View\Model\ViewModel', $view);
    }

    /**
     * Return a form that will allow you to do pretty much anything
     */
    protected function getFormStub()
    {
        return m::mock('\Zend\Form\Form')
            ->shouldReceive('get')
            ->andReturn(
                m::mock('\StdClass')
                    ->shouldReceive('setValueOptions')
                    ->andReturnSelf()
                    ->getMock()
            )
            ->getMock()
            ->shouldDeferMissing();
    }

    protected function getMockRestHelperForDocuments()
    {
        return m::mock('Common\Service\Helper\RestHelperService')
            ->shouldReceive('makeRestCall')
            ->with(
                'DocumentSearchView',
                'GET',
                [
                    'sort' => "issuedDate",
                    'order' => "DESC",
                    'page' => 1,
                    'limit' => 10,
                    'licenceId' => 7
                ],
                m::any() // last param is usually a blank bundle specifier
            )
            ->shouldReceive('makeRestCall')
            ->with(
                'Category',
                'GET',
                [
                    'limit' => 100,
                    'sort' => 'description',
                    'isDocCategory' => true,
                ],
                m::any()
            )
            ->shouldReceive('makeRestCall')
            ->with(
                'SubCategory',
                'GET',
                [
                    'sort'      => 'subCategoryName',
                    'order'     => 'ASC',
                    'page'      => 1,
                    'limit'     => 100,
                    'licenceId' => 7,
                    'isDoc'     => true
                ],
                m::any()
            )
            ->shouldReceive('makeRestCall')
            ->with(
                'RefData',
                'GET',
                [
                    'refDataCategoryId' => 'document_type',
                    'limit'=>100,
                    'sort'=>'description'
                ],
                m::any()
            )
            ->getMock();
    }

    public function testDocumentsActionWithUploadRedirectsToUpload()
    {
        $this->sut = $this->getMock(
            $this->testClass,
            array(
                'getRequest',
                'params',
                'redirect',
                'url',
                'getFromRoute',
                'getCase'
            )
        );

        $request = $this->getMock('\stdClass', ['isPost', 'getPost']);
        $request->expects($this->any())
            ->method('isPost')
            ->will($this->returnValue(true));
        $this->sut->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $params = $this->getMock('\stdClass', ['fromPost', 'fromQuery', 'fromRoute']);
        $params->expects($this->once())
            ->method('fromPost')
            ->with('action')
            ->will($this->returnValue('upload'));
        $this->sut->expects($this->any())
            ->method('params')
            ->will($this->returnValue($params));

        $this->sut->expects($this->any())
            ->method('getFromRoute')
            ->with('case')
            ->will($this->returnValue(1234));

        $this->sut->expects($this->once())
            ->method('getCase')
            ->will(
                $this->returnValue(
                    [
                        'id' => 1234,
                        'licence' => [
                            'id' => 7
                        ]
                    ]
                )
            );

        $redirect = $this->getMock('\stdClass', ['toRoute']);
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with(
                'case_licence_docs_attachments/upload',
                ['case' => 1234, 'licence' => 7]
            );
        $this->sut->expects($this->once())
            ->method('redirect')
            ->will($this->returnValue($redirect));

        $response = $this->sut->documentsAction();
    }

    /**
     * Adds the assertions for the add and edit form tests
     *
     * @param $pageLayout
     * @param $view
     * @param $addEditHelper
     * @param $mockServiceManager
     */
    private function createAddEditAssertions($pageLayout, $view, $addEditHelper, $mockServiceManager)
    {
        $viewChildren = $view->getChildren();
        $headerView = $viewChildren[0];
        $layoutView = $viewChildren[1];
        $innerView = $layoutView->getChildren();

        $this->assertInstanceOf('\Zend\View\Model\ViewModel', $view);
        $this->assertInstanceOf('\Zend\View\Model\ViewModel', $headerView);
        $this->assertInstanceOf('\Zend\View\Model\ViewModel', $layoutView);
        $this->assertInstanceOf('\Zend\View\Model\ViewModel', $innerView[0]);

        $this->assertEquals($view->getTemplate(), 'layout/base');
        $this->assertEquals($headerView->getTemplate(), 'layout/partials/header');
        $this->assertEquals($layoutView->getTemplate(), $pageLayout);
        $this->assertEquals($innerView[0]->getTemplate(), 'crud/form');

        $this->assertEquals(
            $addEditHelper->getForm(),
            $mockServiceManager->get('viewHelperManager')->get('placeholder')->getContainer('form')->getValue()
        );
    }

    /**
     * Get a new SUT
     *
     * @throws \Exception
     * @return \Olcs\Controller\Cases\CaseController
     */
    public function getSut()
    {
        $this->routeMatchHelper = new ControllerRouteMatchHelper();
        $this->pluginManagerHelper = new ControllerPluginManagerHelper();

        $sut = new CaseController();

        if (false === ($sut instanceof \Common\Controller\AbstractSectionController)) {
            throw new \Exception('This system under test does not extend for the correct ultimate abstract');
        }

        if (false === ($sut instanceof \Common\Controller\CrudInterface)) {
            throw new \Exception('This system under test does not implement the correct interface');
        }

        return $sut;
    }
}
