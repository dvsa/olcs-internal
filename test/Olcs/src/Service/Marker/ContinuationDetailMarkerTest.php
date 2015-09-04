<?php

namespace OlcsTest\Service\Marker;

use Mockery as m;

/**
 * ContinuationDetailMarkerTest
 *
 * @author Mat Evans <mat.evans@valtech.co.uk>
 */
class ContinuationDetailMarkerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \Olcs\Service\Marker\ContinuationDetailMarker
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new \Olcs\Service\Marker\ContinuationDetailMarker();
    }

    public function testCanRenderWithNoData()
    {
        $this->assertFalse($this->sut->canRender());
    }

    public function testCanRender()
    {
        $data = [
            'continuationDetail' => ['continuation' => ''],
            'licence' => ['id' => 1],
        ];

        $this->sut->setData($data);

        $this->assertTrue($this->sut->canRender());
    }

    public function testRender()
    {
        $data = [
            'continuationDetail' => ['continuation' => ['year' => '2015', 'month' => '07']],
            'licence' => ['id' => 63],
        ];

        $mockPartialHelper = m::mock(\Zend\View\Helper\Partial::class);

        $mockPartialHelper->shouldReceive('__invoke')
            ->with('partials/marker/continuation', ['dateTime' => new \DateTime('2015-07-01'), 'licenceId' => 63])
            ->once()->andReturn('HTML1');

        $this->sut->setData($data);
        $this->sut->setPartialHelper($mockPartialHelper);

        $this->assertSame('HTML1', $this->sut->render());
    }
}
