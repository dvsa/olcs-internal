<?php

namespace OlcsTest\Form\Model\Form;

use Olcs\TestHelpers\FormTester\AbstractFormValidationTestCase;

/**
 * Class EditUnlicensedGoodsVehicleTest
 *
 * @group FormTests
 */
class EditUnlicensedGoodsVehicleTest extends AbstractFormValidationTestCase
{
    /**
     * @var string The class name of the form being tested
     */
    protected $formName = \Olcs\Form\Model\Form\EditUnlicensedGoodsVehicle::class;

    public function testVrm()
    {
        $element = ['data', 'vrm'];
        $this->assertFormElementRequired($element, true);
        $this->assertFormElementText($element, 1, 20);
    }

    public function testVehiclePlatedWeight()
    {
        $element = ['data', 'platedWeight'];
        $this->assertFormElementRequired($element, false);
        $this->assertFormElementNumber($element, 0, 999999);
    }

    public function testId()
    {
        $this->assertFormElementHidden(['data', 'id']);
    }

    public function testVersion()
    {
        $this->assertFormElementHidden(['data', 'version']);
    }

    public function testSubmit()
    {
        $this->assertFormElementActionButton(['form-actions', 'submit']);
    }

    public function testCancel()
    {
        $this->assertFormElementActionButton(['form-actions', 'cancel']);
    }
}
