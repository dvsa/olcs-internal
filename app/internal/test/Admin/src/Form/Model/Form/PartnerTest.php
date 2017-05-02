<?php

namespace OlcsTest\Form\Model\Form;

use Olcs\TestHelpers\FormTester\AbstractFormValidationTestCase;
use Admin\Form\Model\Form\Partner as PartnerForm;

/**
 * Class PartnerTest
 * @package OlcsTest\FormTest
 * @group FormTests
 */
class PartnerTest extends AbstractFormValidationTestCase
{
    protected $formName = PartnerForm::class;

    public function testDescription()
    {
        $element = ['fields', 'description'];
        $this->assertFormElementText($element, 3, 35);
    }

    public function testContactType()
    {
        $this->assertFormElementHidden(['fields', 'contactType']);
    }

    public function testId()
    {
        $this->assertFormElementHidden(['fields', 'id']);
    }

    public function testVersion()
    {
        $this->assertFormElementHidden(['fields', 'version']);
    }

    public function testSearchPostcode()
    {
        $this->assertFormElementPostcodeSearch(['address', 'searchPostcode']);
    }

    public function testAddressId()
    {
        $this->assertFormElementHidden(['address', 'id']);
    }

    public function testAddressVersion()
    {
        $this->assertFormElementHidden(['address', 'version']);
    }

    public function testAddressLine1()
    {
        $this->assertFormElementRequired(
            ['address', 'addressLine1'],
            true
        );
    }

    public function testAddressLine2()
    {
        $this->assertFormElementRequired(
            ['address', 'addressLine2'],
            false
        );
    }

    public function testAddressLine3()
    {
        $this->assertFormElementRequired(
            ['address', 'addressLine3'],
            false
        );
    }

    public function testAddressLine4()
    {
        $this->assertFormElementRequired(
            ['address', 'addressLine4'],
            false
        );
    }

    public function testTown()
    {
        $this->assertFormElementRequired(
            ['address', 'town'],
            true
        );
    }

    public function testPostcode()
    {
        $this->assertFormElementRequired(
            ['address', 'postcode'],
            false
        );
    }

    public function testCountryCode()
    {
        $this->assertFormElementDynamicSelect(
            ['address', 'countryCode'],
            false
        );
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