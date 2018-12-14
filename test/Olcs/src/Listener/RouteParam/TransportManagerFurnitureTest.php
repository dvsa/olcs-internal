<?php

/**
 * Transport Manager Furniture Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace OlcsTest\Listener\RouteParam;

use Common\Exception\ResourceNotFoundException;
use Common\RefData;
use Common\Service\Cqrs\Command\CommandSender;
use Common\Service\Cqrs\Query\QuerySender;
use Dvsa\Olcs\Transfer\Query\Tm\TransportManager as TransportManagerQry;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParam\TransportManagerFurniture;
use Mockery as m;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;
use Zend\View\Model\ViewModel;

/**
 * Transport Manager Furniture Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class TransportManagerFurnitureTest extends TestCase
{
    /**
     * @var TransportManagerFurniture
     */
    protected $sut;

    protected $mockViewHelperManager;
    protected $mockQuerySender;
    protected $mockCommandSender;

    public function setUp()
    {
        $this->mockViewHelperManager = m::mock(HelperPluginManager::class);
        $this->mockQuerySender = m::mock(QuerySender::class);
        $this->mockCommandSender = m::mock(CommandSender::class);

        $this->sut = new TransportManagerFurniture();

        $sl = m::mock(ServiceLocatorInterface::class);

        $sl->shouldReceive('get')->with('ViewHelperManager')->andReturn($this->mockViewHelperManager);
        $sl->shouldReceive('get')->with('QuerySender')->andReturn($this->mockQuerySender);
        $sl->shouldReceive('get')->with('CommandSender')->andReturn($this->mockCommandSender);

        $this->sut->createService($sl);
    }

    public function testAttach()
    {
        $events = m::mock(EventManagerInterface::class);

        $events->shouldReceive('attach')->once()
            ->with('route.param.transportManager', [$this->sut, 'onTransportManager'], 1)
            ->andReturn('listener');

        $this->sut->attach($events);
    }

    public function testOnTransportManagerFurnitureWithError()
    {
        $this->expectException(ResourceNotFoundException::class);

        $event = m::mock(RouteParam::class);
        $event->shouldReceive('getValue')->andReturn(111);

        $response = m::mock();
        $response->shouldReceive('isOk')->andReturn(false);

        $this->mockQuerySender->shouldReceive('send')->once()
            ->with(m::type(TransportManagerQry::class))
            ->andReturn($response);

        $this->sut->onTransportManager($event);
    }

    public function testOnTransportManagerFurniture()
    {
        $tmStatus = [
            'id' => RefData::TRANSPORT_MANAGER_STATUS_CURRENT,
            'description' => 'Current'
        ];
        $event = m::mock(RouteParam::class);
        $event->shouldReceive('getValue')->andReturn(111);

        $mockPlaceholder = m::mock();
        $mockPlaceholder->shouldReceive('getContainer')
            ->with('pageTitle')
            ->andReturn(
                m::mock()
                ->shouldReceive('set')
                ->once()
                ->with('<a href="url">Bob Smith</a>')
                ->getMock()
            )
            ->shouldReceive('getContainer')
            ->with('horizontalNavigationId')
            ->andReturn(
                m::mock()
                    ->shouldReceive('set')
                    ->once()
                    ->with('transport_manager')
                    ->getMock()
            )
            ->shouldReceive('getContainer')
            ->with('right')
            ->andReturn(
                m::mock()
                    ->shouldReceive('set')
                    ->once()
                    ->with(m::type(ViewModel::class))
                    ->getMock()
            )
            ->shouldReceive('getContainer')
            ->with('status')
            ->andReturn(
                m::mock()
                    ->shouldReceive('set')
                    ->with($tmStatus)
                    ->once()
                    ->getMock()
            );

        $this->mockViewHelperManager->shouldReceive('get')
            ->with('placeholder')->andReturn($mockPlaceholder);

        $mockUrl = m::mock();
        $mockUrl->shouldReceive('__invoke')
            ->with('transport-manager/details', ['transportManager' => 111], [], true)
            ->andReturn('url');

        $this->mockViewHelperManager->shouldReceive('get')
            ->with('url')->andReturn($mockUrl);

        $data = [
            'id' => 111,
            'homeCd' => [
                'person' => [
                    'forename' => 'Bob',
                    'familyName' => 'Smith'
                ]
            ],
            'tmStatus' => $tmStatus
        ];

        $response = m::mock();
        $response->shouldReceive('isOk')->andReturn(true);
        $response->shouldReceive('getResult')->andReturn($data);

        $this->mockQuerySender->shouldReceive('send')->once()
            ->with(m::type(TransportManagerQry::class))
            ->andReturn($response);

        $this->sut->onTransportManager($event);
    }
}
