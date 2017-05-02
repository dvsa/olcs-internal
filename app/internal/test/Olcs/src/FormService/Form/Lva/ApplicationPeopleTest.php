<?php

namespace OlcsTest\FormService\Form\Lva;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Olcs\FormService\Form\Lva\ApplicationPeople;

class ApplicationPeopleTest extends MockeryTestCase
{
    /** @var  ApplicationPeople */
    protected $sut;

    /** @var  m\MockInterface|\Common\Service\Helper\FormHelperService */
    protected $formHelper;
    /** @var  \Common\FormService\FormServiceManager */
    protected $fsm;

    public function setUp()
    {
        $this->formHelper = m::mock(\Common\Service\Helper\FormHelperService::class);
        $this->fsm = m::mock(\Common\FormService\FormServiceManager::class)->makePartial();

        $this->sut = new ApplicationPeople();
        $this->sut->setFormHelper($this->formHelper);
        $this->sut->setFormServiceLocator($this->fsm);
    }

    public function testGetForm()
    {
        $formActions = m::mock();
        $formActions->shouldReceive('has')->with('cancel')->andReturn(true)->once();
        $formActions->shouldReceive('remove')->once()->with('cancel')->once();
        $formActions->shouldReceive('has')->with('save')->andReturn(true)->once();
        $formActions->shouldReceive('remove')->with('save')->once();

        $form = m::mock();
        $form->shouldReceive('has')->with('form-actions')->andReturn(true)->twice();
        $form->shouldReceive('get')->with('form-actions')->andReturn($formActions)->twice();

        $this->formHelper->shouldReceive('createForm')->once()
            ->with('Lva\People')
            ->andReturn($form);

        $this->sut->getForm();
    }
}