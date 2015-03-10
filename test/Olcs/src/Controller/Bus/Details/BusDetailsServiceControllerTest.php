<?php


namespace OlcsTest\Controller\Bus\Details;

use Olcs\Controller\Bus\Details\BusDetailsServiceController;
use Olcs\TestHelpers\ControllerPluginManagerHelper;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use Mockery as m;

/**
 * Class BusDetailsServiceControllerTest
 * @package OlcsTest\Controller\Bus\Details
 */
class BusDetailsServiceControllerTest extends TestCase
{
    /**
     * @var BusDetailsServiceController
     */
    protected $sut;

    /**
     * @var ControllerPluginManagerHelper
     */
    protected $pluginManagerHelper;

    public function setUp()
    {
        $this->pluginManagerHelper = new ControllerPluginManagerHelper();
        $this->sut = new BusDetailsServiceController();

        parent::setUp();
    }

    public function testProcessSave()
    {
        $id = 3;
        $postData = [
            'fields' => [
                'otherServices' => [
                    ['serviceNo' => 'abc'],
                    ['serviceNo' => '']
                ]
            ]
        ];

        $savedData = [
            'id' => $id
        ];

        $mockShortNotice = m::mock('Common\Service\ShortNotice');
        $mockShortNotice->shouldReceive('isShortNotice')->andReturn(true);

        $matcher = function ($data) {
            return $data['fields']['isShortNotice'] == 'Y' && count($data['fields']['otherServices']) == 1;
        };

        $mockDataService = m::mock('Common\Service\Helper\DataHelperService');
        $mockDataService->shouldReceive('processDataMap')->with(
            m::on($matcher),
            m::any(),
            m::any()
        )->andReturn([]);

        $mockRestHelper = m::mock('RestHelper');
        $mockRestHelper->shouldReceive('makeRestCall')
                       ->withAnyArgs()
                       ->andReturn($savedData);

        $mockBusProcessing = m::mock('Processing\Bus');
        $mockBusProcessing->shouldReceive('maybeCreateFee')->with($savedData['id'])->andReturn(true);

        $mockServiceManager = m::mock('\Zend\ServiceManager\ServiceManager');
        $mockServiceManager->shouldReceive('get')->with('Helper\Rest')->andReturn($mockRestHelper);
        $mockServiceManager->shouldReceive('get')->with('Helper\Data')->andReturn($mockDataService);
        $mockServiceManager->shouldReceive('get')->with('Common\Service\ShortNotice')->andReturn($mockShortNotice);
        $mockServiceManager->shouldReceive('get')->with('Processing\Bus')->andReturn($mockBusProcessing);

        $this->sut->setServiceLocator($mockServiceManager);

        $mockPluginManager = $this->pluginManagerHelper->getMockPluginManager(
            [
                'redirect' => 'Redirect',
                'FlashMessenger' => 'FlashMessenger',
                'params' => 'Params'
            ]
        );

        $mockFlashMessenger = $mockPluginManager->get('FlashMessenger', '');
        $mockFlashMessenger->shouldReceive('addSuccessMessage');

        $mockRedirect = $mockPluginManager->get('redirect', '');
        $mockRedirect->shouldReceive('toRoute')->with(
            null,
            ['action'=>'edit'],
            ['code' => '303'],
            true
        )->andReturn('redirectResponse');

        $mockPluginManager->shouldReceive('get')->with('redirect')->andReturn($mockRedirect);

        $mockParams = $mockPluginManager->get('params', '');
        $mockParams->shouldReceive('fromPost')->andReturn($postData);
        $mockParams->shouldReceive('fromRoute')->andReturn(['id' => 5]);

        $this->sut->setPluginManager($mockPluginManager);

        $this->assertEquals('redirectResponse', $this->sut->processSave($postData));
    }
}
