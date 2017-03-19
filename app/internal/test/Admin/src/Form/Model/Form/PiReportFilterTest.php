<?php

namespace AdminTest\Form\Model\Form;

use Olcs\TestHelpers\FormTester\AbstractFormValidationTestCase;

/**
 * Class PiReportFilterTest
 *
 * @group FormTests
 */
class PiReportFilterTest extends AbstractFormValidationTestCase
{
    /**
     * @var string The class name of the form being tested
     */
    protected $formName = \Admin\Form\Model\Form\PiReportFilter::class;

    public function testStartDate()
    {
        $element = [ 'startDate' ];

        $pastYear = date('Y')-1;

        $errorMessages = [
            'dateInvalidDate',
        ];

        $this->assertFormElementValid(
            $element,
            ['day' => 1, 'month' => '2', 'year' => $pastYear]
        );

        $this->assertFormElementNotValid(
            $element,
            ['day' => 'ABC', 'month' => '1', 'year' => $pastYear+2],
            $errorMessages
        );
    }

    public function testEndDate()
    {
        $pastYear = date('Y')-2;

        $element = [ 'endDate' ];

        $errorMessages = [
            'invalidField',
        ];

        $this->assertFormElementNotValid(
            $element,
            ['day' => '1', 'month' => '1', 'year' => $pastYear+2],
            $errorMessages
        );
    }

    public function testTrafficAreas()
    {
        $element = ['trafficAreas'];
        $this->assertFormElementDynamicMultiCheckbox($element);
    }

    public function testGenerate()
    {
        $element = ['filter'];
        $this->assertFormElementActionButton($element);
    }
}
