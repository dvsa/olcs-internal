<?php

namespace OlcsTest\Form\Model\Form;

use Olcs\TestHelpers\FormTester\AbstractFormValidationTestCase;

/**
 * Class AddUnlicensedPsvVehicleTest
 *
 * @group FormTests
 */
class AddUnlicensedPsvVehicleTest extends AbstractFormValidationTestCase
{
    /**
     * @var string The class name of the form being tested
     */
    protected $formName = \Olcs\Form\Model\Form\AddUnlicensedPsvVehicle::class;

    public function testOrganisation()
    {
        $element = ['organisation'];
        $this->assertFormElementHidden($element);
    }

    public function testDataId()
    {
        $element = ['data', 'id'];
        $this->assertFormElementHidden($element);
    }

    public function testDataVersion()
    {
        $element = ['data', 'version'];
        $this->assertFormElementHidden($element);
    }

    public function testDataVrm()
    {
        $element = ['data', 'vrm'];
        $this->assertFormElementRequired($element, true);
        $this->assertFormElementAllowEmpty($element, false);
        $this->assertFormElementText($element, 1, 20);
    }

    public function testAddAnother()
    {
        $element = ['form-actions', 'addAnother'];
        $this->assertFormElementActionButton($element);
    }

    public function testSubmit()
    {
        $element = ['form-actions', 'submit'];
        $this->assertFormElementActionButton($element);
    }

    public function testCancel()
    {
        $element = ['form-actions', 'cancel'];
        $this->assertFormElementActionButton($element);
    }
}
