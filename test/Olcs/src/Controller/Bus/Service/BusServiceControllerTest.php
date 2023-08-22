<?php

/**
 * Bus Service Controller Test
 */
namespace OlcsTest\Controller\Bus\Service;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Table\TableBuilder;
use Laminas\Navigation\Navigation;
use Olcs\Controller\Bus\Service\BusServiceController as Sut;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery as m;
use Common\RefData;

/**
 * Bus Service Controller Test
 */
class BusServiceControllerTest extends MockeryTestCase
{
    protected $sut;
    protected $translationHelper;
    protected $formHelper;
    protected $flashMessengerHelper;
    protected $navigation;
    protected $tableBuilder;

    public function setUp(): void
    {
        $this->translationHelper = m::mock(TranslationHelperService::class);
        $this->formHelper = m::mock(FormHelperService::class);
        $this->flashMessengerHelper =  m::mock(FlashMessengerHelperService::class);
        $this->navigation = m::mock(Navigation::class);
        $this->tableBuilder = m::mock(TableBuilder::class);

        $this->sut = new Sut(
            $this->translationHelper,
            $this->formHelper,
            $this->flashMessengerHelper,
            $this->navigation,
            $this->tableBuilder);
    }

    public function testGetForm()
    {
        $this->sut = m::mock(Sut::class,[$this->translationHelper, $this->formHelper, $this->flashMessengerHelper, $this->navigation, $this->tableBuilder])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $licenceId = 110;
        $params = m::mock()
            ->shouldReceive('fromRoute')->with('licence')->andReturn($licenceId)
            ->getMock();

        $this->sut
            ->shouldReceive('params')
            ->once()
            ->andReturn($params);

        $type = 'foo';

        $mockTableFieldset = m::mock('\Laminas\Form\Fieldset');

        $mockConditionsFieldset = m::mock('\Laminas\Form\Fieldset');
        $mockConditionsFieldset->shouldReceive('get')->with('table')->andReturn($mockTableFieldset);

        $mockForm = m::mock('\Laminas\Form\Form');
        $mockForm->shouldReceive('get')->with('conditions')->andReturn($mockConditionsFieldset);
        $mockForm->shouldReceive('hasAttribute')->with('action')->andReturnNull();
        $mockForm->shouldReceive('setAttribute')->with('action', '');

        $this->formHelper->shouldReceive('createForm')->with($type)->andReturn($mockForm);
        $this->formHelper->shouldReceive('setFormActionFromRequest')->with(
            $mockForm,
            m::type('object')
        )->andReturn($mockForm);
        $this->formHelper->shouldReceive('populateFormTable')->with(
            m::type('object'),
            m::type(TableBuilder::class)
        )->andReturn($mockForm);

        $mockTable = m::mock(TableBuilder::class);

        $this->tableBuilder->shouldReceive('prepareTable')->with(
            m::type('string'),
            m::type('array')
        )->andReturn($mockTable);

        $response = m::mock()
            ->shouldReceive('isServerError')
            ->andReturn(false)
            ->shouldReceive('isClientError')
            ->andReturn(false)
            ->shouldReceive('isOk')
            ->andReturn(true)
            ->shouldReceive('getResult')
            ->andReturn(
                [
                    'results' => [],
                    'count' => 0,
                ]
            )
            ->getMock();

        $this->sut
            ->shouldReceive('handleQuery')
            ->once()
            ->andReturn($response);

        $result = $this->sut->getForm($type);

        $this->assertSame($result, $mockForm);
    }

    /**
     * @dataProvider alterFormForEditDataProvider
     *
     * @param $data
     * @param $readonly
     * @param $timetableRemoved
     * @param $opNotifiedLaPteRemoved
     */
    public function testAlterFormForEdit(
        $data,
        $readonly,
        $timetableRemoved,
        $opNotifiedLaPteRemoved,
        $laShortNoteRemoved
    ) {
        $this->sut = m::mock(Sut::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $response = m::mock();
        $response->shouldReceive('getResult')
            ->once()
            ->andReturn($data);

        $busRegId = 56;
        $params = m::mock()
            ->shouldReceive('fromRoute')->with('busRegId')->andReturn($busRegId)
            ->getMock();

        $this->sut
            ->shouldReceive('handleQuery')
            ->once()
            ->andReturn($response);

        $this->sut
            ->shouldReceive('params')
            ->once()
            ->andReturn($params);

        $mockFieldset = m::mock('\Laminas\Form\Element');
        $mockFieldset->shouldReceive('get')->with('fields')->andReturn($mockFieldset);
        $mockFieldset->shouldReceive('remove')
            ->times($opNotifiedLaPteRemoved ? 1 : 0)
            ->with('opNotifiedLaPte');
        $mockFieldset->shouldReceive('remove')
            ->times($laShortNoteRemoved ? 1 : 0)
            ->with('laShortNote');

        $mockTimetableFieldset = m::mock(\Laminas\Form\Fieldset::class);
        $mockTimetableFieldset->shouldReceive('remove')
            ->with('timetableAcceptable')
            ->times($timetableRemoved ? 1 : 0)
            ->andReturnSelf();
        $mockTimetableFieldset->shouldReceive('remove')
            ->with('mapSupplied')
            ->times($timetableRemoved ? 1 : 0)
            ->andReturnSelf();

        $mockForm = m::mock('\Laminas\Form\Form');
        $mockForm->shouldReceive('get')->with('fields')->andReturn($mockFieldset);
        $mockForm->shouldReceive('get')
            ->with('timetable')
            ->times($timetableRemoved ? 1 : 0)
            ->andReturn($mockTimetableFieldset);
        $mockForm->shouldReceive('setOption')
            ->times($readonly ? 1 : 0)
            ->with('readonly', true);

        $result = $this->sut->alterFormForEdit($mockForm, []);

        $this->assertSame($mockForm, $result);
    }

    public function alterFormForEditDataProvider()
    {
        return [
            [
                [
                    'isReadOnly' => true,
                    'isScottishRules' => true,
                    'isShortNotice' => 'Y',
                    'isCancelled' => 1,
                    'isCancellation' => 0,
                ],
                // $readonly
                true,
                // $timetableRemoved
                true,
                // $opNotifiedLaPteRemoved
                false,
                // $laShortNoteRemoved
                false
            ],
            [
                [
                    'isReadOnly' => true,
                    'isScottishRules' => true,
                    'isShortNotice' => 'Y',
                    'isCancelled' => 0,
                    'isCancellation' => 1,
                ],
                // $readonly
                true,
                // $timetableRemoved
                true,
                // $opNotifiedLaPteRemoved
                false,
                // $laShortNoteRemoved
                false
            ],
            [
                [
                    'isReadOnly' => true,
                    'isScottishRules' => true,
                    'isShortNotice' => 'Y',
                    'isCancelled' => 0,
                    'isCancellation' => 0,
                ],
                // $readonly
                true,
                // $timetableRemoved
                false,
                // $opNotifiedLaPteRemoved
                false,
                // $laShortNoteRemoved
                false
            ],
            [
                [
                    'isReadOnly' => false,
                    'isScottishRules' => false,
                    'isShortNotice' => 'Y',
                    'isCancelled' => 0,
                    'isCancellation' => 0,
                ],
                // $readonly
                false,
                // $timetableRemoved
                false,
                // $opNotifiedLaPteRemoved
                true,
                // $laShortNoteRemoved
                false
            ],
            [
                [
                    'isReadOnly' => false,
                    'isScottishRules' => true,
                    'isShortNotice' => 'N',
                    'isCancelled' => 0,
                    'isCancellation' => 0,
                ],
                // $readonly
                false,
                // $timetableRemoved
                false,
                // $opNotifiedLaPteRemoved
                false,
                // $laShortNoteRemoved
                true
            ],
            [
                [
                    'isReadOnly' => false,
                    'isScottishRules' => false,
                    'isShortNotice' => 'N',
                    'isCancelled' => 0,
                    'isCancellation' => 0,
                ],
                // $readonly
                false,
                // $timetableRemoved
                false,
                // $opNotifiedLaPteRemoved
                true,
                // $laShortNoteRemoved
                true
            ],
        ];
    }
}
