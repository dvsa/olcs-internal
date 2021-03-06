<?php

namespace OlcsTest\Form\Model\Form;

use Olcs\TestHelpers\FormTester\AbstractFormValidationTestCase;

/**
 * Class BusRegUpdateWithdrawnTest
 *
 * @group FormTests
 */
class BusRegUpdateWithdrawnTest extends AbstractFormValidationTestCase
{
    /**
     * @var string The class name of the form being tested
     */
    protected $formName = \Olcs\Form\Model\Form\BusRegUpdateWithdrawn::class;

    public function testReason()
    {
        $element = ['fields', 'reason'];
        $this->assertFormElementDynamicRadio($element, false);
    }

    public function testId()
    {
        $element = ['fields', 'id'];
        $this->assertFormElementHidden($element);
    }

    public function testVersion()
    {
        $element = ['fields', 'version'];
        $this->assertFormElementHidden($element);
    }

    public function testStatus()
    {
        $element = ['fields', 'status'];
        $this->assertFormElementHidden($element);
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
