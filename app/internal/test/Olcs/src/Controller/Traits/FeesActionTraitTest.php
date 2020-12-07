<?php

namespace OlcsTest\Controller\Traits;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery as m;
use OlcsTest\Controller\Traits\Stub\FeesActionTraitStub;

/**
 * @covers \Olcs\Controller\Traits\FeesActionTrait
 */
class FeesActionTraitTest extends MockeryTestCase
{
    /** @var FeesActionTraitStub | m\MockInterface */
    private $sut;

    /** @var \Laminas\Form\FormInterface | m\MockInterface  */
    private $mockForm;
    /** @var \Laminas\Http\Request | m\MockInterface  */
    private $mockReq;

    /** @var \Common\Service\Helper\FormHelperService | m\MockInterface  */
    private $mockFormHlpr;
    /** @var \Laminas\ServiceManager\ServiceLocatorInterface | m\MockInterface  */
    private $mockSm;


    public function setUp(): void
    {
        $this->mockForm = m::mock(\Laminas\Form\FormInterface::class);
        $this->mockReq = m::mock(\Laminas\Http\Request::class);

        $this->mockFormHlpr = m::mock(\Common\Service\Helper\FormHelperService::class);

        $this->mockSm = m::mock(\Laminas\ServiceManager\ServiceLocatorInterface::class);
        $this->mockSm
            ->shouldReceive('get')->with('Helper\Form')->once()->andReturn($this->mockFormHlpr);

        $this->sut = m::mock(FeesActionTraitStub::class)->makePartial();
        $this->sut
            ->shouldReceive('getServiceLocator')->andReturn($this->mockSm)
            ->shouldReceive('getRequest')->andReturn($this->mockReq);
    }

    public function testGetFeeFilterForm()
    {
        $filters = ['unit_filters'];

        $this->mockForm->shouldReceive('setData')->once($filters);

        /** @var \Common\Service\Helper\FormHelperService $mockFormHlpr */
        $this->mockFormHlpr
            ->shouldReceive('createForm')->once()->with('FeeFilter', false)->andReturn($this->mockForm)
            ->shouldReceive('setFormActionFromRequest')->once()->with($this->mockForm, $this->mockReq)->andReturnSelf();

        static::assertSame($this->mockForm, $this->sut->getFeeFilterForm($filters));
    }
}
