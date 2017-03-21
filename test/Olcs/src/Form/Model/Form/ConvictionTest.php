<?php

namespace OlcsTest\Form\Model\Form;

use Common\Form\Elements\Validators\Date;
use Common\Form\Elements\Validators\DateNotInFuture;
use Common\Validator\Date as DateValidator;
use Common\Validator\DateCompare;
use Olcs\TestHelpers\FormTester\AbstractFormValidationTestCase;
use Zend\Validator\StringLength;
use Zend\Form\Element\Select;
use Common\Form\Elements\Custom\OlcsCheckbox;

/**
 * Class ConvictionTest
 *
 * @group FormTests
 */
class ConvictionTest extends AbstractFormValidationTestCase
{
    /**
     * @var string The class name of the form being tested
     */
    protected $formName = \Olcs\Form\Model\Form\Conviction::class;

    public function testDefendantType()
    {
        $this->assertFormElementDynamicSelect(
            ['fields', 'defendantType'],
            true
        );
    }

    public function testPersonFirstname()
    {
        $this->assertFormElementText(['fields', 'personFirstname']);
    }

    public function testPersonLastname()
    {
        $this->assertFormElementText(['fields', 'personLastname']);
    }

    public function testDateOfBirth()
    {
        $element = ['fields', 'birthDate'];
        $this->assertFormElementRequired($element, false);
        $this->assertFormElementAllowEmpty($element, true);

        $this->assertFormElementNotValid(
            $element,
            [
                'year' => '2017',
                'month' => '10',
                'day' => '10',
            ],
            [DateNotInFuture::IN_FUTURE],
            [
                'fields' => [
                    'defendantType' => [ 'def_t_op' => true ],
                ],
            ]
        );

        $this->assertFormElementNotValid(
            $element,
            [
                'year' => '2017',
                'month' => '10',
                'day' => 'XXX',
            ],
            [
                DateValidator::DATE_ERR_CONTAINS_STRING,
                Date::INVALID_DATE
            ],
            [
                'fields' => [
                    'defendantType' => [ 'def_t_op' => true ],
                ],
            ]
        );

        $this->assertFormElementValid(
            $element,
            [
                'year' => '1987',
                'month' => '06',
                'day' => '15',
            ],
            [
                'fields' => [
                    'defendantType' => [ 'def_t_op' => true ],
                ],
            ]
        );
    }

    public function testConvictionCategory()
    {
        $this->assertFormElementDynamicSelect(
            ['fields', 'convictionCategory'],
            false
        );
    }

    public function testCategoryText()
    {
        $element = ['fields', 'categoryText'];

        $this->assertFormElementNotValid(
            $element,
            "",
            [StringLength::TOO_SHORT],
            [
                'fields' => [
                    'convictionCategory' => '',
                ],
            ]
        );

        $this->assertFormElementNotValid(
            $element,
            "abc",
            [StringLength::TOO_SHORT],
            [
                'fields' => [
                    'convictionCategory' => '',
                ],
            ]
        );

        $this->assertFormElementValid(
            $element,
            "abc123",
            [
                'fields' => [
                    'convictionCategory' => '',
                ],
            ]
        );
    }

    public function testOffenceDate()
    {
        $element = ['fields', 'offenceDate'];
        $this->assertFormElementRequired($element, false);

        $this->assertFormElementNotValid(
            $element,
            [
                'year' => 'XXXX',
                'month' => '10',
                'day' => '10',
            ],
            [
                DateValidator::DATE_ERR_CONTAINS_STRING,
                DateValidator::DATE_ERR_YEAR_LENGTH,
                Date::INVALID_DATE,
                DateCompare::NO_COMPARE,
            ],
            [
                'fields' => [
                    'convictionDate' => [
                        'year' => '2017',
                        'month' => '10',
                        'day' => '5',
                    ],
                ],
            ]
        );

        $this->assertFormElementNotValid(
            $element,
            [
                'year' => '2017',
                'month' => '10',
                'day' => '10',
            ],
            [
                DateNotInFuture::IN_FUTURE,
                DateCompare::NO_COMPARE,
                DateCompare::NOT_LTE
            ],
            [
                'fields' => [
                    'convictionDate' => [
                        'year' => '2017',
                        'month' => '10',
                        'day' => '5',
                    ],
                ],
            ]
        );

        $this->assertFormElementValid(
            $element,
            [
                'year' => '2016',
                'month' => '10',
                'day' => '10',
            ],
            [
                'fields' => [
                    'convictionDate' => [
                        'year' => '2016',
                        'month' => '10',
                        'day' => '10',
                    ],
                ],
            ]
        );
    }

    public function testConvictionDate()
    {
        $this->assertFormElementDate(['fields', 'convictionDate']);
    }

    public function testMsi()
    {
        $element = ['fields', 'msi'];
        $this->assertFormElementRequired($element, true);
        $this->assertFormElementType($element, Select::class);
    }

    public function testCourt()
    {
        $element = ['fields', 'court'];
        $this->assertFormElementRequired($element, false);
        $this->assertFormElementText($element, 2, 70);
    }

    public function testPenalty()
    {
        $element = ['fields', 'penalty'];
        $this->assertFormElementRequired($element, false);
        $this->assertFormElementText($element, 0, 255);
    }

    public function testCosts()
    {
        $element = ['fields', 'costs'];
        $this->assertFormElementRequired($element, false);
        $this->assertFormElementText($element, 2, 255);
    }

    public function testNotes()
    {
        $element = ['fields', 'notes'];
        $this->assertFormElementRequired($element, false);
        $this->assertFormElementText($element, 5, 4000);
    }

    public function testTakenIntoConsideration()
    {
        $element = ['fields', 'takenIntoConsideration'];
        $this->assertFormElementRequired($element, false);
        $this->assertFormElementText($element, 5, 4000);
    }

    public function testIsDeclared()
    {
        $element = ['fields', 'isDeclared'];
        $this->assertFormElementRequired($element, true);
        $this->assertFormElementType($element, Select::class);
    }

    public function testIsDealtWith()
    {
        $element = ['fields', 'isDealtWith'];
        $this->assertFormElementRequired($element, true);
        $this->assertFormElementType($element, OlcsCheckbox::class);
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
