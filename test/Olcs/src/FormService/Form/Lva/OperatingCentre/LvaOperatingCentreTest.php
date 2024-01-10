<?php

namespace OlcsTest\FormService\Form\Lva\OperatingCentre;

use Laminas\Form\ElementInterface;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Laminas\Form\Fieldset;
use Laminas\Form\Form;
use Olcs\FormService\Form\Lva\OperatingCentre\LvaOperatingCentre;
use Laminas\Validator\Identical as ValidatorIdentical;
use Common\RefData;
use Common\Data\Mapper\Lva\OperatingCentre as OperatingCentreMapper;

/**
 * Lva Operating Centre Test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class LvaOperatingCentreTest extends MockeryTestCase
{
    protected $sut;

    protected $formHelper;

    public function setUp(): void
    {
        $this->formHelper = m::mock('\Common\Service\Helper\FormHelperService');

        $this->sut = new LvaOperatingCentre($this->formHelper);
    }

    /**
     * @dataProvider alterFormProvider
     */
    public function testAlterForm($appliedVia)
    {
        $form = m::mock(Form::class)
            ->shouldReceive('get')
            ->with('address')
            ->andReturn(
                m::mock(ElementInterface::class)
                    ->shouldReceive('get')
                    ->with('postcode')
                    ->andReturn(
                        m::mock(ElementInterface::class)
                            ->shouldReceive('setOption')
                            ->with('shouldEscapeMessages', false)
                            ->once()
                            ->getMock()
                    )
                    ->once()
                    ->getMock()
            )
            ->once()
            ->shouldReceive('get')
            ->with('advertisements')
            ->andReturn(
                m::mock(ElementInterface::class)
                    ->shouldReceive('get')
                    ->with('radio')
                    ->andReturn(
                        m::mock(ElementInterface::class)
                        ->shouldReceive('getValueOptions')
                        ->andReturn(['foo' => 'bar', OperatingCentreMapper::VALUE_OPTION_AD_UPLOAD_LATER => 'cake'])
                        ->once()
                        ->shouldReceive('setValueOptions')
                        ->with(['foo' => 'bar'])
                        ->once()
                        ->getMock()
                    )
                    ->once()
                    ->shouldReceive('setLabel')
                    ->with('application_operating-centres_authorisation-sub-action.advertisements.adPlaced')
                    ->once()
                ->getMock()
            )
            ->shouldReceive('get')
            ->with('data')
            ->andReturn(
                m::mock(ElementInterface::class)
                    ->shouldReceive('get')
                    ->with('guidance')
                    ->andReturn(
                        m::mock(ElementInterface::class)
                            ->shouldReceive('setValue')
                            ->with('lva-operating-centre-newspaper-advert')
                            ->once()
                            ->getMock()
                    )
                    ->once()
                    ->shouldReceive('get')
                    ->with('permission')
                    ->andReturn(
                        m::mock(ElementInterface::class)
                            ->shouldReceive('setLabel')
                            ->with('')
                            ->once()
                            ->getMock()
                    )
                    ->once()
                    ->getMock()
            )
            ->getMock();

        $this->formHelper
            ->shouldReceive('removeValidator')
            ->with($form, 'data->permission->permission', ValidatorIdentical::class)
            ->once()
            ->shouldReceive('removeValidator')
            ->with($form, 'advertisements->uploadedFileCount', \Common\Validator\ValidateIf::class)
            ->once()
            ->shouldReceive('remove')
            ->with($form, 'advertisements->adSendByPostContent')
            ->once()
            ->shouldReceive('remove')
            ->with($form, 'advertisements->adPlacedLaterContent')
            ->once()
            ->getMock();

        $form->shouldReceive('getInputFilter')
            ->andReturn(
                m::mock(ElementInterface::class)
                    ->shouldReceive('get')
                    ->with('address')
                    ->andReturn(
                        m::mock(ElementInterface::class)
                            ->shouldReceive('get')
                            ->with('postcode')
                            ->andReturn(
                                m::mock(ElementInterface::class)
                                    ->shouldReceive('setRequired')
                                    ->with(false)
                                    ->once()
                                    ->getMock()
                            )
                            ->once()
                            ->getMock()
                    )
                    ->getMock()
            )
            ->once()
            ->getMock();

        $params = [
            'isPsv' => false,
            'canAddAnother' => true,
            'canUpdateAddress' => true,
            'wouldIncreaseRequireAdditionalAdvertisement' => false,
            'appliedVia' => $appliedVia,
        ];
        $this->sut->alterForm($form, $params);
    }

    public function alterFormProvider()
    {
        return [
            [RefData::APPLIED_VIA_POST],
            [['id' => RefData::APPLIED_VIA_POST]]
        ];
    }
}
