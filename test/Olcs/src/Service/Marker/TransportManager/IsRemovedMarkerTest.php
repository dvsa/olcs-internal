<?php

namespace OlcsTest\Service\Marker;

use Mockery as m;

/**
 * IsRemovedMarkerTest
 *
 * @author Josh Curtis <josh@josh-curtis.co.uk>
 */
class IsRemovedMarkerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \Olcs\Service\Marker\TransportManager\Rule50Marker
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new \Olcs\Service\Marker\TransportManager\IsRemovedMarker();
    }

    public function testCanRenderWithNoData()
    {
        $this->assertFalse($this->sut->canRender());
    }

    public function testCanRenderWithData()
    {
        $this->sut->setData(['transportManager' => ['removedDate' => 'notnull']]);

        $this->assertTrue($this->sut->canRender());
    }

    public function testRender()
    {
        $this->sut->setData(['transportManager' => ['removedDate' => '1990-2-10 10:00']]);

        $mockPartialHelper = m::mock(\Zend\View\Helper\Partial::class);
        $mockPartialHelper->shouldReceive('__invoke')
            ->with(
                'partials/marker/transport-manager/is-removed',
                [
                    'date' => new \DateTime('1990-2-10 10:00')
                ]
            )->once()->andReturn('HTML5');

        $this->sut->setPartialHelper($mockPartialHelper);

        $this->assertSame('HTML5', $this->sut->render());
    }
}
