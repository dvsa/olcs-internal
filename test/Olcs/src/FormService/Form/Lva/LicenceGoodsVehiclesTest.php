<?php

namespace OlcsTest\FormService\Form\Lva;

use Common\Form\Form;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Olcs\FormService\Form\Lva\LicenceGoodsVehicles;
use OlcsTest\Bootstrap;
use ZfcRbac\Service\AuthorizationService;

/**
 * Licence Goods Vehicles Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class LicenceGoodsVehiclesTest extends MockeryTestCase
{
    protected $sut;

    protected $formHelper;

    protected $formService;

    protected $sm;

    public function setUp(): void
    {
        $this->sm = Bootstrap::getServiceManager();
        $this->formHelper = m::mock('\Common\Service\Helper\FormHelperService');
        $this->formService = m::mock('\Common\FormService\FormServiceManager')->makePartial();

        $this->sut = new LicenceGoodsVehicles($this->formHelper, m::mock(AuthorizationService::class), $this->formService);
    }

    public function testGetForm()
    {
        // Params
        $mockTable = m::mock('\Common\Service\Table\TableBuilder');
        $isCrudPressed = true;

        // Mocks
        $mockForm = m::mock();
        $mockTableElement = m::mock('\Laminas\Form\Fieldset');
        $mockValidator = m::mock();

        $this->sm->setService('oneRowInTablesRequired', $mockValidator);

        // Expectations
        $this->formHelper->shouldReceive('createForm')
            ->with('Lva\GoodsVehicles')
            ->andReturn($mockForm)
            ->shouldReceive('populateFormTable')
            ->with($mockTableElement, $mockTable);

        $formActions = m::mock();
        $formActions->shouldReceive('has')->with('saveAndContinue')->andReturn(true);
        $formActions->shouldReceive('remove')->once()->with('saveAndContinue');

        $mockForm->shouldReceive('has')->with('form-actions')->andReturn(true);
        $mockForm->shouldReceive('get')->with('form-actions')->andReturn($formActions);

        $mockForm->shouldReceive('get')->with('table')->andReturn($mockTableElement);

        $mockForm->shouldReceive('getInputFilter->get->get->getValidatorChain->attach')
            ->with($mockValidator);

        // <<--- START SUT::alterForm
        $mockLicence = m::mock(Form::class);
        $this->formService->setService('lva-licence', $mockLicence);

        $mockLicenceVariationVehicles = m::mock(Form::class);
        $this->formService->setService('lva-licence-variation-vehicles', $mockLicenceVariationVehicles);

        $mockLicence->shouldReceive('alterForm')
            ->with($mockForm);

        $mockLicenceVariationVehicles->shouldReceive('alterForm')
            ->with($mockForm);
        // <<--- END SUT::alterForm

        $mockTableElement->shouldReceive('get->getValue')
            ->andReturn(10);

        $mockValidator->shouldReceive('setRows')
            ->with([10])
            ->shouldReceive('setCrud')
            ->with(true);

        $form = $this->sut->getForm($mockTable, $isCrudPressed);

        $this->assertSame($mockForm, $form);
    }
}
