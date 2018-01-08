<?php

namespace OlcsTest\Form\Model\Form;

use Olcs\TestHelpers\FormTester\AbstractFormValidationTestCase;

/**
 * Class RevokeTest
 *
 * @group FormTests
 */
class RevokeTest extends AbstractFormValidationTestCase
{
    /**
     * @var string The class name of the form being tested
     */
    protected $formName = \Olcs\Form\Model\Form\Revoke::class;

    public function testReasons()
    {
        $this->assertFormElementDynamicSelect(['fields', 'reasons'], true);
    }

    public function testPresidingTc()
    {
        $this->assertFormElementDynamicSelect(['fields', 'presidingTc'], true);
    }

    public function testPresidingTcService()
    {
        $this->assertServiceEquals(['fields', 'presidingTc'], \Olcs\Service\Data\UserListInternal::class);
    }

    public function testPtrAgreedDate()
    {
        $this->assertFormElementDate(['fields', 'ptrAgreedDate']);
    }

    public function testClosedDate()
    {
        $this->assertFormElementDate(['fields', 'closedDate']);
    }

    public function testComment()
    {
        $element = ['fields', 'comment'];
        $this->assertFormElementRequired($element, false);
        $this->assertFormElementText($element, 5, 4000);
    }

    public function testCase()
    {
        $this->assertFormElementHidden(['fields', 'case']);
    }

    public function testId()
    {
        $this->assertFormElementHidden(['fields', 'id']);
    }

    public function testVersion()
    {
        $this->assertFormElementHidden(['fields', 'version']);
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
