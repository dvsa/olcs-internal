<?php

namespace OlcsTest\FormService\Form\Lva;

use Common\Service\Table\TableBuilder;
use Mockery as m;
use Olcs\FormService\Form\Lva\Addresses;
use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Olcs\FormService\Form\Lva\Addresses
 */
class AddressesTest extends AbstractLvaFormServiceTestCase
{
    protected $classToTest = Addresses::class;

    /** @var  \Olcs\FormService\Form\Lva\Addresses */
    protected $sut;

    public function testGetForm()
    {
        // Mocks
        $mockForm = m::mock(\Zend\Form\Form::class);

        $this->formHelper
            ->shouldReceive('createForm')->andReturn($mockForm);

        //  check set label
        $mockElmSaveBtn = m::mock(\Zend\Form\ElementInterface::class);
        $mockElmSaveBtn->shouldReceive('setLabel')->with('internal.save.button')->once();

        $mockFsActions = m::mock(\Zend\Form\FieldsetInterface::class);
        $mockFsActions->shouldReceive('get')->once()->andReturn($mockElmSaveBtn);

        $mockForm->shouldReceive('get')->with('form-actions')->once()->andReturn($mockFsActions);

        //  check change email setting
        $mockInputFilter = m::mock(InputFilter::class)
            ->shouldReceive('get')
            ->with('contact')
            ->once()
            ->andReturn(
                m::mock()
                    ->shouldReceive('get')
                    ->with('email')
                    ->andReturn(
                        m::mock(\Zend\InputFilter\Input::class)
                            ->shouldReceive('setRequired')->once()->with(false)->andReturnSelf()
                            ->shouldReceive('setAllowEmpty')->once()->with(true)
                            ->getMock()
                    )
                    ->getMock()
            )
            ->getMock();

        $mockForm->shouldReceive('getInputFilter')->once()->andReturn($mockInputFilter);

        //  call
        $form = $this->sut->getForm(
            [
                'typeOfLicence' => [
                    'licenceType' => 'ltyp_sn',
                ],
                'corrPhoneContacts' => [
                    'unit_PhoneContacts',
                ],
            ]
        );

        static::assertSame($mockForm, $form);
    }
}
