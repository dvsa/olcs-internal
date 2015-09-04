<?php

namespace OlcsTest\View\Helper;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Class RenderMarkersTest
 *
 * @author Mat Evans <mat.evans@valtech.co.uk>
 */
class RenderMarkersTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Olcs\View\Helper\RenderMarkers
     */
    private $sut;

    public function setUp()
    {
        $this->sut = new \Olcs\View\Helper\RenderMarkers();
    }

    public function testGetSetMarkerService()
    {
        $this->sut->setMarkerService('MS');
        $this->assertSame('MS', $this->sut->getMarkerService());
    }

    public function testInvokeAll()
    {
        $mockMarkerService = m::mock();
        $this->sut->setMarkerService($mockMarkerService);

        $mockMarker1 = m::mock(\Olcs\Service\Marker\LicenceStatusMarker::class);
        $mockMarker2 = m::mock(\Olcs\Service\Marker\BusRegEbsrMarker::class);

        $mockMarkerService->shouldReceive('getMarkers')->with()->once()->andReturn(
            [$mockMarker1, $mockMarker2]
        );

        $mockMarker1->shouldReceive('render')->with()->once()->andReturn('HTML1');
        $mockMarker2->shouldReceive('render')->with()->once()->andReturn('HTML2');

        $this->assertSame(
            '<div class="notice-container">HTML1HTML2</div>',
            $this->sut->__invoke()
        );
    }

    public function testInvokeSpecific()
    {
        $mockMarkerService = m::mock();
        $this->sut->setMarkerService($mockMarkerService);

        $mockMarker1 = m::mock(\Olcs\Service\Marker\LicenceStatusMarker::class);
        $mockMarker2 = m::mock(\Olcs\Service\Marker\BusRegEbsrMarker::class);

        $mockMarkerService->shouldReceive('getMarkers')->with()->once()->andReturn(
            [$mockMarker1, $mockMarker2]
        );

        $mockMarker2->shouldReceive('render')->with()->once()->andReturn('HTML2');

        $this->assertSame(
            '<div class="notice-container">HTML2</div>',
            $this->sut->__invoke([get_class($mockMarker2)])
        );
    }
}
