<?php

/**
 * Application Operating Centres Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace OlcsTest\FormService\Form\Lva\OperatingCentres;

use Olcs\FormService\Form\Lva\OperatingCentres\ApplicationOperatingCentres;
use Common\FormService\FormServiceInterface;
use Common\FormService\FormServiceManager;
use Common\Service\Table\TableBuilder;
use OlcsTest\Bootstrap;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Laminas\Form\Element;
use Laminas\Form\Fieldset;
use Laminas\Form\Form;
use Laminas\Http\Request;
use Common\Service\Helper\FormHelperService;

/**
 * Application Operating Centres Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class ApplicationOperatingCentresTest extends MockeryTestCase
{
    protected $form;

    /**
     * @var ApplicationOperatingCentres
     */
    protected $sut;

    protected $mockFormHelper;

    protected $tableBuilder;

    public function setUp(): void
    {
        $this->tableBuilder = m::mock();

        $sm = Bootstrap::getServiceManager();
        $sm->setService('Table', $this->tableBuilder);

        $fsm = m::mock(FormServiceManager::class)->makePartial();
        $fsm->shouldReceive('getServiceLocator')
            ->andReturn($sm);

        $this->form = m::mock(Form::class);

        $lvaApplication = m::mock(FormServiceInterface::class);
        $lvaApplication->shouldReceive('alterForm')
            ->once()
            ->with($this->form);

        $fsm->setService('lva-application', $lvaApplication);

        $this->mockFormHelper = m::mock(FormHelperService::class);
        $this->mockFormHelper->shouldReceive('createForm')
            ->once()
            ->with('Lva\OperatingCentres')
            ->andReturn($this->form);

        $this->sut = new ApplicationOperatingCentres();
        $this->sut->setFormHelper($this->mockFormHelper);
        $this->sut->setFormServiceLocator($fsm);
    }

    public function testGetForm()
    {
        $params = [
            'operatingCentres' => [],
            'canHaveSchedule41' => true,
            'canHaveCommunityLicences' => true,
            'isPsv' => false,
            'trafficArea' => null,
            'niFlag' => null,
            'possibleTrafficAreas' => 'POSSIBLE_TRAFFIC_AREAS',
            'possibleEnforcementAreas' => 'POSSIBLE_ENFORCEMENT_AREAS',
        ];

        $this->mockPopulateFormTable([]);

        $this->mockFormHelper->shouldReceive('getValidator->setMessage')
            ->with('OperatingCentreNoOfOperatingCentres.required', 'required');

        $mockTaElement = m::mock();
        $this->form->shouldReceive('get')->with('dataTrafficArea')->once()->andReturn($mockTaElement);

        $mockTaElement->shouldReceive('get->setValueOptions')->with('POSSIBLE_TRAFFIC_AREAS')->once();
        $mockTaElement->shouldReceive('remove')->with('trafficAreaSet')->once();

        $mockTaElement->shouldReceive('get->setValueOptions')->with('POSSIBLE_ENFORCEMENT_AREAS')->once();

        $form = $this->sut->getForm($params);
        $this->assertSame($this->form, $form);
    }

    protected function mockPopulateFormTable($data)
    {
        $rows = [
            ['noOfLgvVehiclesRequired' => 1]
        ];

        $table = m::mock(TableBuilder::class);
        $table->shouldReceive('getRows')
            ->withNoArgs()
            ->andReturn($rows);

        $tableElement = m::mock(Fieldset::class);
        $tableElement->shouldReceive('get')
            ->with('table')
            ->andReturnSelf()
            ->shouldReceive('getTable')
            ->withNoArgs()
            ->andReturn($table);

        $this->form->shouldReceive('get')
            ->with('table')
            ->andReturn($tableElement);

        $this->tableBuilder->shouldReceive('prepareTable')
            ->with('lva-operating-centres', $data, [])
            ->andReturn($table);

        $this->mockFormHelper->shouldReceive('populateFormTable')
            ->with($tableElement, $table);

        return $tableElement;
    }
}
