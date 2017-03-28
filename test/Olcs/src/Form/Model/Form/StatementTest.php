<?php

namespace OlcsTest\Form\Model\Form;

use Olcs\TestHelpers\FormTester\AbstractFormValidationTestCase;
use Zend\Validator\Date;
use Common\Validator\Date as CommonDateValidator;


/**
 * Class StatementTest
 *
 * @group FormTests
 */
class StatementTest extends AbstractFormValidationTestCase
{
    /**
     * @var string The class name of the form being tested
     */
    protected $formName = \Olcs\Form\Model\Form\Statement::class;

    public function testStatementType()
    {
        $this->assertFormElementDynamicSelect(
            ['fields', 'statementType'],
            true
        );
    }

    public function testVrm()
    {
        $this->assertFormElementRequired(['fields', 'vrm'], true);
    }

    public function testRequestorsForename()
    {
        $element = ['fields', 'requestorsForename'];
        $this->assertFormElementRequired($element, true);
        $this->assertFormElementText($element, 2, 35);
    }

    public function testRequestorsFamilyName()
    {
        $element = ['fields', 'requestorsFamilyName'];
        $this->assertFormElementRequired($element, true);
        $this->assertFormElementText($element, 2, 35);
    }

    public function testRequestorsBody()
    {
        $element = ['fields', 'requestorsBody'];
        $this->assertFormElementRequired($element, true);
        $this->assertFormElementText($element, 2, 40);
    }

    public function testStoppedDate()
    {
        $element = ['fields', 'stoppedDate'];

        // Invalid date format and no field
        $this->assertFormElementNotValid(
            $element,
            [
                'year'  => 'XXX',
                'month' => date('m'),
                'day'   => date('j'),
            ],
            [
                CommonDateValidator::DATE_ERR_CONTAINS_STRING,
                CommonDateValidator::DATE_ERR_YEAR_LENGTH,
                Date::INVALID_DATE,
                'invalidField',
            ]
        );

        $this->assertFormElementValid(
            $element,
            [
                'year'  => date('Y') - 1,
                'month' => date('m'),
                'day'   => date('j'),
            ],
            [
                'fields' => [
                    'requestedDate' => [
                        'year'  => date('Y') + 1,
                        'month' => date('m'),
                        'day'   => date('j'),
                    ],
                ],
            ]
        );
    }

    public function testRequestedDate()
    {
        $this->assertFormElementDate(['fields', 'requestedDate']);
    }

    public function testIssuedDate()
    {
        $this->assertFormElementDate(['fields', 'issuedDate']);
    }

    public function testContactType()
    {
        $this->assertFormElementDynamicSelect(['fields', 'contactType'], true);
    }

    public function testContactDetailsType()
    {
        $this->assertFormElementHidden(['fields', 'contactDetailsType']);
    }

    public function testContactDetailsId()
    {
        $this->assertFormElementHidden(['fields', 'contactDetailsId']);
    }

    public function testContactDetailsVersion()
    {
        $this->assertFormElementHidden(['fields', 'contactDetailsVersion']);
    }

    public function testPersonId()
    {
        $this->assertFormElementHidden(['fields', 'personId']);
    }

    public function testPersonVersion()
    {
        $this->assertFormElementHidden(['fields', 'personVersion']);
    }

    public function testAuthorisersDecision()
    {
        $element = ['fields', 'authorisersDecision'];
        $this->assertFormElementRequired($element, true);
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

    public function testSearchPostcode()
    {
        $this->assertFormElementPostcodeSearch([
            'requestorsAddress',
            'searchPostcode',
        ]);
    }

    public function testAddressId()
    {
        $this->assertFormElementHidden(['requestorsAddress', 'id']);
    }

    public function testAddressVersion()
    {
        $this->assertFormElementHidden(['requestorsAddress', 'version']);
    }

    public function testAddressLine1()
    {
        $this->assertFormElementRequired(
            ['requestorsAddress', 'addressLine1'],
            true
        );
    }

    public function testAddressLine2()
    {
        $this->assertFormElementRequired(
            ['requestorsAddress', 'addressLine2'],
            false
        );
    }

    public function testAddressLine3()
    {
        $this->assertFormElementRequired(
            ['requestorsAddress', 'addressLine3'],
            false
        );
    }

    public function testAddressLine4()
    {
        $this->assertFormElementRequired(
            ['requestorsAddress', 'addressLine4'],
            false
        );
    }

    public function testTown()
    {
        $this->assertFormElementRequired(
            ['requestorsAddress', 'town'],
            true
        );
    }

    public function testPostcode()
    {
        $this->assertFormElementRequired(
            ['requestorsAddress', 'postcode'],
            false
        );
    }

    public function testCountryCode()
    {
        $this->assertFormElementDynamicSelect(
            ['requestorsAddress', 'countryCode'],
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
