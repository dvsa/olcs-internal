<?php

/**
 * Bus Service Controller Test
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace OlcsTest\Controller\Bus\Service;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Olcs\TestHelpers\ControllerRouteMatchHelper;
use Olcs\TestHelpers\ControllerAddEditHelper;
use Olcs\TestHelpers\ControllerPluginManagerHelper;
use Mockery as m;

/**
 * Bus Service Controller Test
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class BusServiceControllerTest extends AbstractHttpControllerTestCase
{
    protected $sut;

    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__.'/../../../../../../config/application.config.php'
        );
        $this->routeMatchHelper = new ControllerRouteMatchHelper();
        $this->pluginManagerHelper = new ControllerPluginManagerHelper();

        $this->sut = new \Olcs\Controller\Bus\Service\BusServiceController();

        parent::setUp();
    }

    public function testRedirectToIndex()
    {
        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'params' => 'Params',
                'url' => 'Url',
                'redirect' => 'Redirect'
            ]
        );

        $mockRedirectPlugin = $mockPluginManager->get('redirect', '');
        $mockRedirectPlugin->shouldReceive('toRoute')->with(
            m::type('string'),
            [],
            [],
            true
        )->andReturn('redirectResponse');

        $this->sut->setPluginManager($mockPluginManager);
        $this->assertEquals('redirectResponse', $this->sut->redirectToIndex());
    }

    public function testProcessLoad()
    {
        $data = [
            'timetableAcceptable' => 'Y',
            'mapSupplied' => 'Y',
            'routeDescription' => 'foo',
            'trcConditionChecked' => 'Y',
            'trcNotes' => 'bar',
            'variationReasons' => [
                0 => [
                    'description' => 'Reason one'
                ],
                1 => [
                    'description' => 'Reason one'
                ]
            ]
        ];
        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'params' => 'Params'
            ]
        );
        $mockParamsPlugin = $mockPluginManager->get('params', '');
        $mockParamsPlugin->shouldReceive('fromQuery')->with('case', "")->andReturn('');
        $mockParamsPlugin->shouldReceive('fromRoute')->with('case', "")->andReturn('');

        $this->sut->setPluginManager($mockPluginManager);

        $result = $this->sut->processLoad($data);

        $this->assertArrayHasKey('timetable', $result);
        $this->assertArrayHasKey('conditions', $result);
    }

    public function testGetForm()
    {
        $type = 'foo';

        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'params' => 'Params'
            ]
        );
        $licenceId = 110;
        $mockParamsPlugin = $mockPluginManager->get('params', '');
        $mockParamsPlugin->shouldReceive('fromRoute')->with('licence')->andReturn($licenceId);

        $this->sut->setPluginManager($mockPluginManager);

        $mockTableFieldset = m::mock('\Zend\Form\Fieldset');

        $mockConditionsFieldset = m::mock('\Zend\Form\Fieldset');
        $mockConditionsFieldset->shouldReceive('get')->with('table')->andReturn($mockTableFieldset);

        $mockForm = m::mock('\Zend\Form\Form');
        $mockForm->shouldReceive('get')->with('conditions')->andReturn($mockConditionsFieldset);
        $mockForm->shouldReceive('hasAttribute')->with('action')->andReturnNull();
        $mockForm->shouldReceive('setAttribute')->with('action', '');

        $mockFormHelper = m::mock('Common\Form\View\Helper\Form');
        $mockFormHelper->shouldReceive('createForm')->with('BusRegisterService')->andReturn($mockForm);
        $mockFormHelper->shouldReceive('populateFormTable')->with(
            m::type('object'),
            m::type('array')
        )->andReturn($mockForm);

        $mockTableService = m::mock('\Common\Service\Table\TableFactory');
        $mockTableService->shouldReceive('prepareTable')->with(
            m::type('string'),
            m::type('array')
        )->andReturn(['tabledata']);

        $mockRestHelper = m::mock('RestHelper');
        $mockRestHelper->shouldReceive('makeRestCall')->withAnyArgs()->andReturn(['Results' => []]);

        $mockServiceManager = m::mock('\Zend\ServiceManager\ServiceManager');
        $mockServiceManager->shouldReceive('get')->with('Helper\Rest')->andReturn($mockRestHelper);

        $mockSl = m::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockSl->shouldReceive('get')->with('Helper\Form')->andReturn($mockFormHelper);
        $mockSl->shouldReceive('get')->with('Table')->andReturn($mockTableService);
        $mockSl->shouldReceive('get')->with('Helper\Rest')->andReturn($mockRestHelper);

        $this->sut->setServiceLocator($mockSl);

        $result = $this->sut->getForm($type);

        $this->assertSame($result, $mockForm);
    }

    public function testAlterFormNotCancelled()
    {
        $this->markTestSkipped();

        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'params' => 'Params'
            ]
        );

        $mockData = [
            'licence' => [
            ],
            'status' => [
                'id' => 'breg_s_registered'
            ],
            'busNoticePeriod' => [
                'id' => 1
            ],
        ];

        $busRegId = 2;
        $mockParamsPlugin = $mockPluginManager->get('params', '');
        $mockParamsPlugin->shouldReceive('getParam')->with('case', "")->andReturn('');
        $mockParamsPlugin->shouldReceive('fromRoute')->with('busRegId')->andReturn($busRegId);

        $this->sut->setPluginManager($mockPluginManager);

        $mockFieldset = m::mock('\Zend\Form\Element');
        $mockFieldset->shouldReceive('get')->with('fields')->andReturn($mockFieldset);
        $mockFieldset->shouldReceive('remove')->with('opNotifiedLaPteHidden');

        $mockForm = m::mock('\Zend\Form\Form');
        $mockForm->shouldReceive('get')->with('fields')->andReturn($mockFieldset);
        $mockForm->shouldReceive('setOption')->never()->with('readonly', true);

        $mockRestHelper = m::mock('RestHelper');
        $mockRestHelper->shouldReceive('makeRestCall')->withAnyArgs()->andReturn($mockData);

        $mockBusRegService = m::mock('Common\Service\Data\BusReg');
        $mockBusRegService->shouldReceive('isLatestVariation')->once()->with($busRegId)->andReturn(true);

        $mockSl = m::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockSl->shouldReceive('get')->with('Helper\Rest')->andReturn($mockRestHelper);
        $mockSl->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockSl->shouldReceive('get')->with('Common\Service\Data\BusReg')->andReturn($mockBusRegService);

        $this->sut->setServiceLocator($mockSl);

        $result = $this->sut->alterForm($mockForm);

        $this->assertSame($result, $mockForm);

    }

    public function testAlterFormScotlandRules()
    {
        $this->markTestSkipped();

        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'params' => 'Params'
            ]
        );

        $mockData = [
            'licence' => [],
            'status' => [
                'id' => 'breg_s_cancelled'
            ],
            'busNoticePeriod' => [
                'id' => 1
            ],
        ];
        $mockOcAddressOption = [99 => 'some OC address'];
        $busRegId = 2;

        $mockParamsPlugin = $mockPluginManager->get('params', '');
        $mockParamsPlugin->shouldReceive('getParam')->with('case', "")->andReturn('');
        $mockParamsPlugin->shouldReceive('fromRoute')->with('busRegId')->andReturn($busRegId);

        $this->sut->setPluginManager($mockPluginManager);

        $mockFieldset = m::mock('\Zend\Form\Element');
        $mockFieldset->shouldReceive('get')->with('fields')->andReturn($mockFieldset);
        $mockFieldset->shouldReceive('remove')->with('opNotifiedLaPteHidden');

        $mockOcField = m::mock('\Zend\Form\Element');
        $mockOcField->shouldReceive('getValueOptions')->andReturn($mockOcAddressOption);

        $mockForm = m::mock('\Zend\Form\Form');
        $mockForm->shouldReceive('setOption')->once()->with('readonly', true);
        $mockForm->shouldReceive('remove')->with('timetable');
        $mockForm->shouldReceive('get')->with('fields')->andReturn($mockFieldset);

        $mockRestHelper = m::mock('RestHelper');
        $mockRestHelper->shouldReceive('makeRestCall')->withAnyArgs()->andReturn($mockData);

        $mockBusRegService = m::mock('Common\Service\Data\BusReg');
        $mockBusRegService->shouldReceive('isLatestVariation')->once()->with($busRegId)->andReturn(false);

        $mockSl = m::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockSl->shouldReceive('get')->with('Helper\Rest')->andReturn($mockRestHelper);
        $mockSl->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockSl->shouldReceive('get')->with('Common\Service\Data\BusReg')->andReturn($mockBusRegService);

        $this->sut->setServiceLocator($mockSl);

        $result = $this->sut->alterForm($mockForm);

        $this->assertSame($result, $mockForm);

    }

    public function testAlterFormNotScotland()
    {
        $this->markTestSkipped();

        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'params' => 'Params'
            ]
        );

        $mockData = [
            'licence' => [],
            'status' => [
                'id' => 'breg_s_cancelled'
            ],
            'busNoticePeriod' => [
                'id' => 2
            ],
        ];
        $busRegId = 2;

        $mockParamsPlugin = $mockPluginManager->get('params', '');
        $mockParamsPlugin->shouldReceive('getParam')->with('case', "")->andReturn('');
        $mockParamsPlugin->shouldReceive('fromRoute')->with('busRegId')->andReturn($busRegId);

        $this->sut->setPluginManager($mockPluginManager);

        $mockFieldset = m::mock('\Zend\Form\Element');
        $mockFieldset->shouldReceive('get')->with('fields')->andReturn($mockFieldset);
        $mockFieldset->shouldReceive('remove')->with('opNotifiedLaPte');

        $mockForm = m::mock('\Zend\Form\Form');
        $mockForm->shouldReceive('setOption')->once()->with('readonly', true);
        $mockForm->shouldReceive('remove')->with('timetable');
        $mockForm->shouldReceive('get')->with('fields')->andReturn($mockFieldset);

        $mockRestHelper = m::mock('RestHelper');
        $mockRestHelper->shouldReceive('makeRestCall')->withAnyArgs()->andReturn($mockData);

        $mockBusRegService = m::mock('Common\Service\Data\BusReg');
        $mockBusRegService->shouldReceive('isLatestVariation')->once()->with($busRegId)->andReturn(false);

        $mockSl = m::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockSl->shouldReceive('get')->with('Helper\Rest')->andReturn($mockRestHelper);
        $mockSl->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockSl->shouldReceive('get')->with('Common\Service\Data\BusReg')->andReturn($mockBusRegService);

        $this->sut->setServiceLocator($mockSl);

        $result = $this->sut->alterForm($mockForm);

        $this->assertSame($result, $mockForm);

    }
}
