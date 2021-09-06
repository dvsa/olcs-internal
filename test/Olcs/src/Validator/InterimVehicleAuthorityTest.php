<?php

namespace OlcsTest\Validator;

use Olcs\Validator\InterimVehicleAuthority;

class InterimVehicleAuthorityTest extends \PHPUnit\Framework\TestCase
{
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new InterimVehicleAuthority();
    }

    /**
     * @dataProvider isValidProvider
     */
    public function testIsValid($value, $formData, $expected, $expectedErrors)
    {
        $this->assertEquals($expected, $this->sut->isValid($value, $formData));
        $this->assertEquals($expectedErrors, $this->sut->getMessages());
    }

    public function isValidProvider()
    {
        return [
            [
                6,
                null,
                false,
                ['vehicleAuthExceeded' => 'The interim vehicle authority cannot exceed the total vehicle authority'],
            ],
            [
                6,
                [],
                false,
                ['vehicleAuthExceeded' => 'The interim vehicle authority cannot exceed the total vehicle authority'],
            ],
            [
                6,
                ['totAuthVehicles' => 0],
                false,
                ['vehicleAuthExceeded' => 'The interim vehicle authority cannot exceed the total vehicle authority'],
            ],
            [
                6,
                ['totAuthVehicles' => 5],
                false,
                ['vehicleAuthExceeded' => 'The interim vehicle authority cannot exceed the total vehicle authority'],
            ],
            [
                6,
                ['totAuthVehicles' => 6, 'isVariation' => true],
                true,
                [],
            ],
            [
                6,
                ['totAuthVehicles' => 10, 'isVariation' => true],
                true,
                [],
            ],
            [
                0,
                ['totAuthVehicles' => 10, 'isVariation' => true],
                true,
                [],
            ],
            [
                0,
                ['totAuthVehicles' => 10, 'isVariation' => false],
                false,
                ['valueBelowOne' => 'The input is not greater or equal than \'1\''],
            ],
            [
                0,
                ['totAuthVehicles' => 0, 'isVariation' => true],
                true,
                [],
            ],
            [
                0,
                ['totAuthVehicles' => 0, 'isVariation' => false],
                false,
                ['valueBelowOne' => 'The input is not greater or equal than \'1\''],
            ],
        ];
    }
}
