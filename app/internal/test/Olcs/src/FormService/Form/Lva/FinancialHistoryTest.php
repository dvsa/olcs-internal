<?php

namespace OlcsTest\FormService\Form\Lva;

use Common\Form\Form;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Olcs\FormService\Form\Lva\FinancialHistory;

/**
 * @covers Olcs\FormService\Form\Lva\FinancialHistory
 */
class FinancialHistoryTest extends MockeryTestCase
{
    /** @var  FinancialHistory */
    protected $sut;

    /** @var  m\MockInterface|\Common\Service\Helper\FormHelperService */
    protected $formHelper;
    /** @var  \Common\FormService\FormServiceManager */
    protected $fsm;


    public function setUp(): void
    {
        $this->formHelper = m::mock(\Common\Service\Helper\FormHelperService::class);
        $this->fsm = m::mock(\Common\FormService\FormServiceManager::class)->makePartial();

        $this->sut = new FinancialHistory();
        $this->sut->setFormHelper($this->formHelper);
        $this->sut->setFormServiceLocator($this->fsm);
    }

    public function testGetForm()
    {
        /** @var \Laminas\Http\Request $request */
        $request = m::mock(\Laminas\Http\Request::class);

        // Mocks
        $mockForm = m::mock(Form::class);

        $formActions = m::mock(Form::class);
        $formActions->shouldReceive('get->setLabel')->once();

        $mockForm->shouldReceive('get')->with('form-actions')->andReturn($formActions);

        $this->formHelper->shouldReceive('createFormWithRequest')
            ->with('Lva\FinancialHistory', $request)
            ->andReturn($mockForm);

        $form = $this->sut->getForm($request, []);

        $this->assertSame($mockForm, $form);
    }
}
