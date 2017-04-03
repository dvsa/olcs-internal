<?php

namespace OlcsTest\Form\Model\Form;

use Olcs\TestHelpers\FormTester\AbstractFormValidationTestCase;
use Zend\Validator\GreaterThan;

/**
 * Class LicenceStatusDecisionMessagesTest
 *
 * @group FormTests
 */
class LicenceStatusDecisionMessagesTest extends AbstractFormValidationTestCase
{
    /**
     * @var string The class name of the form being tested
     */
    protected $formName = \Olcs\Form\Model\Form\LicenceStatusDecisionMessages::class;

    public function testMessage()
    {
        $element = ['messages', 'message'];
        $this->assertFormElementHtml($element);
    }

    public function testContinue()
    {
        $this->assertFormElementActionButton(['form-actions', 'continue']);
    }

    public function testCancel()
    {
        $this->assertFormElementActionButton(['form-actions', 'cancel']);
    }
}
