<?php

namespace OlcsTest\Form\Model\Form\Lva;

use Olcs\TestHelpers\FormTester\AbstractFormValidationTestCase;

/**
 * Class AddGoodsVehicleTest
 *
 * @group FormTests
 */
class AddGoodsVehicleTest extends AbstractFormValidationTestCase
{
    /**
     * @var string The class name of the form being tested
     */
    protected $formName = \Olcs\Form\Model\Form\Lva\AddGoodsVehicle::class;

    public function testId()
    {
        $this->assertFormElementHidden(['data', 'id']);
    }

    public function testVersion()
    {
        $this->assertFormElementHidden(['data', 'version']);
    }

    public function testVrm()
    {
        $this->assertFormElementVrm(['data', 'vrm']);
    }

    public function testPlatedWeight()
    {
        $this->assertFormElementVehiclePlatedWeight(['data', 'platedWeight']);
    }

    public function testLicenceVehicleId()
    {
        $this->assertFormElementHidden(['licence-vehicle', 'id']);
    }

    public function testLicenceVehicleVersion()
    {
        $this->assertFormElementHidden(['licence-vehicle', 'version']);
    }

    /**
     * @todo unskip https://jira.dvsacloud.uk/browse/VOL-2309
     */
    public function testLicenceVehicleReceivedDate()
    {
        $this->markTestSkipped();
        $element = ['licence-vehicle', 'receivedDate'];
        $this->assertFormElementDate($element);
        $this->assertFormElementRequired($element, false);
    }

    public function testLicenceVehicleSpecifiedDateTime()
    {
        $element = ['licence-vehicle', 'specifiedDate'];

        $tomorrow = new \DateTimeImmutable('+1 day');

        $this->assertFormElementDateTimeValidCheck(
            $element,
            [
                'year'   => $tomorrow->format('Y'),
                'month'  => $tomorrow->format('m'),
                'day'    => $tomorrow->format('j'),
                'hour'   => 12,
                'minute' => 12,
                'second' => 12,
            ]
        );
    }

    /**
     * @todo unskip https://jira.dvsacloud.uk/browse/VOL-2309
     */
    public function testLicenceVehicleRemovalDate()
    {
        $this->markTestSkipped();
        $element = ['licence-vehicle', 'removalDate'];
        $this->assertFormElementDate($element);
        $this->assertFormElementRequired($element, false);
    }

    public function testDiscNumber()
    {
        $element = ['licence-vehicle', 'discNo'];
        $this->assertFormElementText($element);
        $this->assertFormElementRequired($element, false);
    }

    public function testAddAnother()
    {
        $this->assertFormElementActionButton(
            ['form-actions', 'addAnother']
        );
    }

    public function testSubmit()
    {
        $this->assertFormElementActionButton(
            ['form-actions', 'submit']
        );
    }

    public function testCancel()
    {
        $this->assertFormElementActionButton(
            ['form-actions', 'cancel']
        );
    }
}
