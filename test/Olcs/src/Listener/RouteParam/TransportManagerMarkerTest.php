<?php

/**
 * Transport Manager Markers Service Test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace OlcsTest\Listener\RouteParam;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use OlcsTest\Bootstrap;
use Olcs\Listener\RouteParam\TransportManagerMarker;
use Mockery as m;
use Olcs\Listener\RouteParams;
use Olcs\Event\RouteParam;

/**
 * Transport Manager Markers Service Test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class TransportManagerMarkerTest extends MockeryTestCase
{
    protected $sut;

    public function setUp()
    {
        $this->sut = new TransportManagerMarker();

        $this->mockQueryService = m::mock();
        $this->mockAnnotationBuilderService = m::mock();

        $this->sut->setAnnotationBuilderService($this->mockAnnotationBuilderService);
        $this->sut->setQueryService($this->mockQueryService);
    }

    /**
     * Test attach
     *
     * @group transportManagerMarker
     */
    public function testAttach()
    {
        $mockEventManager = m::mock('Zend\EventManager\EventManagerInterface')
            ->shouldReceive('attach')
            ->with(RouteParams::EVENT_PARAM . 'transportManager', [$this->sut, 'onTransportManagerMarker'], 1)
            ->once()
            ->shouldReceive('attach')
            ->with(RouteParams::EVENT_PARAM . 'licence', [$this->sut, 'onLicenceTransportManagerMarker'], 1)
            ->once()
            ->shouldReceive('attach')
            ->with(RouteParams::EVENT_PARAM . 'application', [$this->sut, 'onApplicationTransportManagerMarker'], 1)
            ->once()
            ->getMock();

        $this->sut->attach($mockEventManager);
    }

    /**
     * Test create service
     *
     * @group transportManagerMarker
     */
    public function testCreateService()
    {
        $mockSl = m::mock(\Zend\ServiceManager\ServiceLocatorInterface::class);
        $mockMarkerService = m::mock(\Olcs\Service\Marker\MarkerService::class);
        $mockQueryService = m::mock();
        $mockAnnotationBuilderService = m::mock();

        $mockSl->shouldReceive('get')->with(\Olcs\Service\Marker\MarkerService::class)->once()
            ->andReturn($mockMarkerService);
        $mockSl->shouldReceive('get')->with('TransferAnnotationBuilder')->once()
            ->andReturn($mockAnnotationBuilderService);
        $mockSl->shouldReceive('get')->with('QueryService')->once()->andReturn($mockQueryService);

        $obj = $this->sut->createService($mockSl);

        $this->assertSame($mockAnnotationBuilderService, $obj->getAnnotationBuilderService());
        $this->assertSame($mockMarkerService, $obj->getMarkerService());
        $this->assertSame($mockQueryService, $obj->getQueryService());

        $this->assertInstanceOf('Olcs\Listener\RouteParam\TransportManagerMarker', $obj);
    }

    protected function mockQuery($expectedDtoParams, $result = false)
    {
        $mockResponse = m::mock();

        $this->mockAnnotationBuilderService->shouldReceive('createQuery')->once()->andReturnUsing(
            function ($dto) use ($expectedDtoParams) {
                $this->assertSame($expectedDtoParams, $dto->getArrayCopy());
                return 'QUERY';
            }
        );

        $this->mockQueryService->shouldReceive('send')->with('QUERY')->once()->andReturn($mockResponse);
        if ($result === false) {
            $mockResponse->shouldReceive('isOk')->with()->once()->andReturn(false);
        } else {
            $mockResponse->shouldReceive('isOk')->with()->once()->andReturn(true);
            $mockResponse->shouldReceive('getResult')->with()->once()
                ->andReturn(['result' => $result, 'results' => $result]);
        }
    }

    /**
     * Test on transport manager marker
     *
     * @group transportManagerMarker
     */
    public function testOnTransportManagerMarker()
    {
        $mockMarkerService = m::mock(\Olcs\Service\Marker\MarkerService::class);
        $this->sut->setMarkerService($mockMarkerService);

        $this->mockQuery(['user' => null, 'application' => null, 'transportManager' => 12], 'TMAs');
        $mockMarkerService->shouldReceive('addData')->with('transportManagerApplications', 'TMAs')->once();

        $this->mockQuery(['licence' => null, 'transportManager' => 12], 'TMLs');
        $mockMarkerService->shouldReceive('addData')->with('transportManagerLicences', 'TMLs')->once();

        $event = new RouteParam();
        $event->setValue(12);

        $this->sut->onTransportManagerMarker($event);
    }

    /**
     * Test on licence transport manager marker
     *
     * @group transportManagerMarker
     */
    public function testOnLicenceTransportManagerMarker()
    {
        $mockMarkerService = m::mock(\Olcs\Service\Marker\MarkerService::class);
        $this->sut->setMarkerService($mockMarkerService);

        $this->mockQuery(['licence' => 18, 'transportManager' => null], 'TMLs');
        $mockMarkerService->shouldReceive('addData')->with('transportManagerLicences', 'TMLs')->once();

        $event = new RouteParam();
        $event->setValue(18);

        $this->sut->onLicenceTransportManagerMarker($event);
    }

    public function testOnLicenceTransportManagerMarkerQueryError()
    {
        $mockMarkerService = m::mock(\Olcs\Service\Marker\MarkerService::class);
        $this->sut->setMarkerService($mockMarkerService);

        $this->mockQuery(['licence' => 18, 'transportManager' => null], false);

        $event = new RouteParam();
        $event->setValue(18);

        $this->setExpectedException(\RuntimeException::class);

        $this->sut->onLicenceTransportManagerMarker($event);
    }

    /**
     * Test on application transport manager marker
     *
     * @group transportManagerMarker
     */
    public function testOnApplicationTransportManagerMarker()
    {
        $mockMarkerService = m::mock(\Olcs\Service\Marker\MarkerService::class);
        $this->sut->setMarkerService($mockMarkerService);

        $this->mockQuery(['user' => null, 'application' => 534, 'transportManager' => null], 'TMAs');
        $mockMarkerService->shouldReceive('addData')->with('transportManagerApplications', 'TMAs')->once();

        $event = new RouteParam();
        $event->setValue(534);

        $this->sut->onApplicationTransportManagerMarker($event);
    }

    public function testOnApplicationTransportManagerMarkerQueryError()
    {
        $mockMarkerService = m::mock(\Olcs\Service\Marker\MarkerService::class);
        $this->sut->setMarkerService($mockMarkerService);

        $this->mockQuery(['user' => null, 'application' => 534, 'transportManager' => null], false);

        $event = new RouteParam();
        $event->setValue(534);

        $this->setExpectedException(\RuntimeException::class);

        $this->sut->onApplicationTransportManagerMarker($event);
    }
}
