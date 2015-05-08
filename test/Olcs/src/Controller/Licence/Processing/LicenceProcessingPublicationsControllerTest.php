<?php
namespace OlcsTest\Controller\Licence\Processing;

use Zend\Form\Annotation\AnnotationBuilder;
use Olcs\Controller\Licence\Processing\LicenceProcessingPublicationsController;
use Mockery as m;
use Olcs\TestHelpers\ControllerPluginManagerHelper;
use Olcs\TestHelpers\ControllerRouteMatchHelper;
use Zend\View\Helper\Placeholder;
use Zend\Form\Form;

/**
 * Class LicenceProcessingPublicationsControllerTest
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class LicenceProcessingPublicationsControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ControllerPluginManagerHelper
     */
    protected $pluginManagerHelper;

    /**
     * @var ControllerRouteMatchHelper
     */
    protected $routeMatchHelper;

    public function setUp()
    {
        $this->sut = new LicenceProcessingPublicationsController();
        $this->pluginManagerHelper = new ControllerPluginManagerHelper();
        $this->routeMatchHelper = new ControllerRouteMatchHelper();

        parent::setUp();
    }

    /**
     * Tests the index action
     */
    public function testIndexAction()
    {
        $licenceId = 7;
        $licenceDescription = 'Licence desc';
        $licenceOrganisation = 'Organisation name';
        $licenceNo = 'OB123456';

        $form = new Form();

        $mockLicenceData = [
            'id' => $licenceId,
            'cases' => [],
            'licNo' => $licenceNo,
            'goodsOrPsv' => [
                'id' => 'lcat_psv'
            ],
            'status' => [
                'description' => $licenceDescription
            ],
            'organisation' => [
                'name' => $licenceOrganisation
            ]

        ];

        $action = null;
        $defaultPage = 1;
        $defaultSort = 'createdOn';
        $defaultOrder = 'DESC';
        $defaultLimit = 10;

        $listParams = [
            'page' => $defaultPage,
            'limit' => $defaultLimit,
            'sort' => $defaultSort,
            'order' => $defaultOrder,
            'licence' => $licenceId
        ];

        $mockListData = ['Results' => [0 => ['id' => 1]], 'Count' => 1];

        $event = $this->routeMatchHelper->getMockRouteMatch(
            array(
                'controller' => 'publications',
                'action' => null,
                'licence' => $licenceId
            )
        );

        $this->sut->setEvent($event);

        //mock plugin manager
        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'params' => 'Params',
                'url' => 'url',
                'Olcs\Service\Marker\MarkerPluginManager' => 'Olcs\Service\Marker\MarkerPluginManager'
            ]
        );

        //mock url helper
        $mockPluginManager->shouldReceive('get')->with('url')->andReturnSelf();

        //publication link service
        $mockPublicationLink = m::mock('Common\Service\Data\PublicationLink');
        $mockPublicationLink->shouldReceive('fetchList')->with($listParams)->andReturn($mockListData);

        //route params
        $mockParams = $mockPluginManager->get('params', '');
        $mockParams->shouldReceive('fromRoute')->with('licence')->andReturn($licenceId);

        $mockParams->shouldReceive('fromPost')->with('action')->andReturn($action);

        $this->sut->setPluginManager($mockPluginManager);

        $mockLicenceService = m::mock('Common\Service\Data\Licence');
        $mockLicenceService->shouldReceive('fetchLicenceData')->with($licenceId)->andReturn($mockLicenceData);

        //mock table builder
        $mockTableBuilder = m::mock('Common\Service\Table\TableBuilder');
        $mockTableBuilder->shouldReceive('buildTable')->withAnyArgs();

        $mockRouter = m::mock('Zend\Mvc\Router\Http\TreeRouteStack');

        $formAnnotationBuilder = new AnnotationBuilder();

        //mock form helper
        $mockFormHelper = m::mock('Helper\Form');
        $mockFormHelper->shouldReceive('createForm')->andReturn($form);

        // mock scripts
        $mockScripts = m::mock('\Common\Service\Script\ScriptFactory');
        $mockScripts->shouldReceive('loadFiles')->with(['table-actions']);

        //mock service manager
        $mockServiceManager = m::mock('\Zend\ServiceManager\ServiceManager');
        $mockServiceManager->shouldReceive('get')->with('Table')->andReturn($mockTableBuilder);
        $mockServiceManager->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockServiceManager->shouldReceive('get')
            ->with('Common\Service\Data\PublicationLink')
            ->andReturn($mockPublicationLink);
        $mockServiceManager->shouldReceive('get')->with('router')->andReturn($mockRouter);
        $mockServiceManager->shouldReceive('get')->with('FormAnnotationBuilder')->andReturn($formAnnotationBuilder);
        $mockServiceManager->shouldReceive('get')->with('Helper\Form')->andReturn($mockFormHelper);
        $mockServiceManager->shouldReceive('get')
            ->with('Common\Service\Data\Licence')
            ->andReturn($mockLicenceService);
        $mockServiceManager->shouldReceive('get')->with('Script')->andReturn($mockScripts);

        $this->mockMarkerPluginCalls($mockServiceManager);

        $this->sut->setServiceLocator($mockServiceManager);

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $this->sut->indexAction());
    }

    /**
     * Tests editAction()
     *
     * @param $pubStatus
     * @param $formName
     *
     * @dataProvider editActionProvider
     */
    public function testEditAction($pubStatus, $formName)
    {
        $id = 1;
        $version = 2;

        $licenceId = 7;
        $licenceDescription = 'Licence desc';
        $licenceOrganisation = 'Organisation name';
        $licenceNo = 'OB123456';

        $postArray = [
            'fields' => [
                'text1' => 'text 1 amend',
                'text2' => 'text 2 amend',
                'text3' => 'text 3 amend',
                'id' => $id,
                'version' => $version
            ]
        ];

        $form = m::mock('Zend\Form\Form');
        $form->shouldReceive('getData')->andReturn($postArray);
        $form->shouldReceive('hasAttribute');
        $form->shouldReceive('setAttribute');
        $form->shouldReceive('getFieldsets')->andReturn([]);
        $form->shouldReceive('setData')->withAnyArgs();
        $form->shouldReceive('isValid')->andReturn(true);
        $form->shouldReceive('bind');

        $mockLicenceData = [
            'id' => $licenceId,
            'cases' => [],
            'licNo' => $licenceNo,
            'goodsOrPsv' => [
                'id' => 'lcat_psv'
            ],
            'status' => [
                'description' => $licenceDescription
            ],
            'organisation' => [
                'name' => $licenceOrganisation
            ]

        ];

        $publicationData = [
            'id' => $id,
            'version' => $version,
            'text1' => 'text 1',
            'text2' => 'text 2',
            'text3' => 'text 3',
            'publication' => [
                'pubType' => 'A & D',
                'publicationNo' => 6128,
                'pubDate' => '2014-10-30',
                'pubStatus' => [
                    'description' => 'Publication status',
                    'id' => $pubStatus
                ],
                'trafficArea' => [
                    'name' => 'Traffic area name'
                ],
            ],
            'publicationSection' => [
                'description' => 'Section description'
            ]
        ];

        $this->sut->getRequest()->setMethod('post');
        $this->sut->getRequest()->getPost()->set('fields', $postArray['fields']);

        //mock plugin manager
        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'params' => 'Params',
                'redirect' => 'Redirect'
            ]
        );

        //route params
        $mockParams = $mockPluginManager->get('params', '');
        $mockParams->shouldReceive('fromRoute')->with('id')->andReturn($id);
        $mockParams->shouldReceive('fromRoute')->with('licence')->andReturn($licenceId);
        $mockParams->shouldReceive('fromPost')->andReturn($postArray);

        $mockRedirect = $mockPluginManager->get('redirect', '');
        $mockRedirect->shouldReceive('toRouteAjax')->withAnyArgs()->andReturn('redirectResponse');

        $mockPluginManager->shouldReceive('get')->with('redirect')->andReturn($mockRedirect);

        //publication link service
        $mockPublicationLink = m::mock('Common\Service\Data\PublicationLink');
        $mockPublicationLink->shouldReceive('fetchOne')->with($id)->andReturn($publicationData);
        $mockPublicationLink->shouldReceive('update')->with($id, $postArray['fields'])->andReturn($id);

        //licence service
        $mockLicence = m::mock('Common\Service\Data\Licence');
        $mockLicence->shouldReceive('fetchLicenceData')->with($licenceId)->andReturn($mockLicenceData);

        $this->sut->setPluginManager($mockPluginManager);

        $stringHelper = new \Common\Service\Helper\StringHelperService();

        $olcsCustomForm = m::mock('\Common\Service\Helper\FormHelperService');
        $olcsCustomForm->shouldReceive('createForm')->with($formName)->andReturn($form);

        $mockRouter = m::mock('Zend\Mvc\Router\Http\TreeRouteStack');

        //placeholders
        $placeholder = new Placeholder();

        //add placeholders to view helper
        $mockViewHelperManager = new \Zend\View\HelperPluginManager();
        $mockViewHelperManager->setService('placeholder', $placeholder);

        //mock service manager
        $mockServiceManager = m::mock('\Zend\ServiceManager\ServiceManager');
        $mockServiceManager->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockServiceManager->shouldReceive('get')
            ->with('Common\Service\Data\PublicationLink')
            ->andReturn($mockPublicationLink);
        $mockServiceManager->shouldReceive('get')->with('Common\Service\Data\Licence')->andReturn($mockLicence);
        $mockServiceManager->shouldReceive('get')->with('Helper\String')->andReturn($stringHelper);
        $mockServiceManager->shouldReceive('get')->with('Helper\Form')->andReturn($olcsCustomForm);
        $mockServiceManager->shouldReceive('get')->with('viewHelperManager')->andReturn($mockViewHelperManager);
        $mockServiceManager->shouldReceive('get')->with('router')->andReturn($mockRouter);

        $this->mockMarkerPluginCalls($mockServiceManager);

        $this->sut->setServiceLocator($mockServiceManager);

        $this->assertEquals('redirectResponse', $this->sut->editAction());
    }

    /**
     * Data provider for testEditAction
     *
     * @return array
     */
    public function editActionProvider()
    {
        return [
            ['pub_s_new', 'Publication'],
            ['pub_s_generated', 'PublicationNotNew'],
            ['pub_s_printed', 'PublicationNotNew']
        ];
    }

    public function testProcessSave()
    {
        $id = 1;
        $version = 2;

        $fields = [
            'text1' => 'text 1',
            'text2' => 'text 2',
            'text3' => 'text 3',
            'id' => $id,
            'version' => $version
        ];

        $data = [
            'fields' => $fields
        ];

        //publication link service
        $mockPublicationLink = m::mock('Common\Service\Data\PublicationLink');
        $mockPublicationLink->shouldReceive('update')->with($id, $fields)->andReturn($id);

        //mock service manager
        $mockServiceManager = m::mock('\Zend\ServiceManager\ServiceManager');
        $mockServiceManager->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockServiceManager->shouldReceive('get')
            ->with('Common\Service\Data\PublicationLink')
            ->andReturn($mockPublicationLink);

        $this->sut->setServiceLocator($mockServiceManager);

        $this->assertNull($this->sut->processSave($data));
    }

    /**
     * Tests the delete action
     */
    public function testDeleteAction()
    {
        $id = 1;

        //mock plugin manager
        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'params' => 'Params',
                'FlashMessenger' => 'FlashMessenger',
                'redirect' => 'Redirect'
            ]
        );

        //publication link service
        $mockPublicationLink = m::mock('Common\Service\Data\PublicationLink');
        $mockPublicationLink->shouldReceive('delete')->with($id)->andReturn([]);

        //mock service manager
        $mockServiceManager = m::mock('\Zend\ServiceManager\ServiceManager');
        $mockServiceManager->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockServiceManager->shouldReceive('get')
            ->with('Common\Service\Data\PublicationLink')
            ->andReturn($mockPublicationLink);

        //route params
        $mockParams = $mockPluginManager->get('params', '');
        $mockParams->shouldReceive('fromRoute')->with('id')->andReturn($id);

        //flash messenger
        $mockFlashMessenger = $mockPluginManager->get('FlashMessenger', '');
        $mockFlashMessenger->shouldReceive('addErrorMessage')->once();

        $mockRedirect = $mockPluginManager->get('redirect', '');
        $mockRedirect->shouldReceive('toRouteAjax')->andReturn('redirectResponse');

        $this->sut->setPluginManager($mockPluginManager);
        $this->sut->setServiceLocator($mockServiceManager);

        $this->assertEquals('redirectResponse', $this->sut->deleteAction());
    }

    /**
     * @dataProvider serviceAndResourceNotFoundProvider
     *
     * @param $expectedException
     * @param $message
     */
    public function testServiceAndResourceNotFoundExceptions($expectedException, $message)
    {
        $id = 1;

        //mock plugin manager
        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'params' => 'Params',
                'FlashMessenger' => 'FlashMessenger',
                'redirect' => 'Redirect'
            ]
        );

        //publication link service
        $mockPublicationLink = m::mock('Common\Service\Data\PublicationLink');

        //publication link shouldn't try to delete
        $class = 'Common\Exception\\' . $expectedException;
        $mockPublicationLink->shouldReceive('delete')->andThrow(new $class($message));

        //mock service manager
        $mockServiceManager = m::mock('\Zend\ServiceManager\ServiceManager');
        $mockServiceManager->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockServiceManager->shouldReceive('get')
            ->with('Common\Service\Data\PublicationLink')
            ->andReturn($mockPublicationLink);

        //route params
        $mockParams = $mockPluginManager->get('params', '');
        $mockParams->shouldReceive('fromRoute')->with('id')->andReturn($id);

        //flash messenger
        $mockFlashMessenger = $mockPluginManager->get('FlashMessenger', '');
        $mockFlashMessenger->shouldReceive('addErrorMessage')->with($message)->once();

        $mockRedirect = $mockPluginManager->get('redirect', '');
        $mockRedirect->shouldReceive('toRouteAjax')->andReturn('redirectResponse');

        $mockPluginManager->shouldReceive('get')->with('redirect')->andReturn($mockRedirect);

        $this->sut->setPluginManager($mockPluginManager);
        $this->sut->setServiceLocator($mockServiceManager);

        $this->assertEquals('redirectResponse', $this->sut->deleteAction());
    }

    /**
     * Data provider for testServiceAndResourceNotFoundExceptions()
     *
     * @return array
     */
    public function serviceAndResourceNotFoundProvider()
    {
        return [
            [
                'DataServiceException',
                'Error message'
            ],
            [
                'ResourceNotFoundException',
                'Error message'
            ],
            [
                'BadRequestException',
                'Error message'
            ]
        ];
    }

    public function testAddAction()
    {
        $this->assertEquals(false, $this->sut->addAction());
    }

    /**
     * Helper function to mock out all calls that are made to generate markers
     *
     * @param Mockery\MockInterface
     */
    protected function mockMarkerPluginCalls($mockServiceManager)
    {
        $mockServiceManager->shouldReceive('get')
            ->with('Olcs\Service\Marker\MarkerPluginManager')
            ->andReturn(
                m::mock()
                    ->shouldReceive('get')
                    ->with('Olcs\Service\Marker\LicenceMarkers')
                    ->andReturn(
                        m::mock()
                        ->shouldReceive('generateMarkerTypes')
                        ->getMock()
                    )
                    ->getMock()
            );
        $mockServiceManager->shouldReceive('get')
            ->with('Helper\LicenceStatus')
            ->andReturn(
                m::mock()
                    ->shouldReceive('getCurrentOrPendingRulesForLicence')
                    ->getMock()
            );
        $mockServiceManager->shouldReceive('get')
            ->with('Entity\ContinuationDetail')
            ->andReturn(
                m::mock()
                    ->shouldReceive('getContinuationMarker')
                    ->getMock()
            );
    }
}
