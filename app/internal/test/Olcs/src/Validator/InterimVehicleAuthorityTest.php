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
    public function testIsValid($value, $formData, $expected)
    {
        $this->assertEquals($expected, $this->sut->isValid($value, $formData));
    }

    public function isValidProvider()
    {
        return [
            [6, ['totAuthVehicles' => 5, 'isVariation' => true], false],
            [4, ['totAuthVehicles' => 2, 'isVariation' => true], false],
            [3, ['totAuthVehicles' => 0, 'isVariation' => true], false],
            [4, ['totAuthVehicles' => 5, 'isVariation' => true], true],
            [10, ['totAuthVehicles' => 10, 'isVariation' => true], true],
            [4, ['totAuthVehicles' => 0, 'isVariation' => true], false],
            [0, ['totAuthVehicles' => 0, 'isVariation' => true], true],
            [0, ['totAuthVehicles' => 7, 'isVariation' => true], true],
            [0, ['totAuthVehicles' => 5, 'isVariation' => true], true],
            [0, ['totAuthVehicles' => 5, 'isVariation' => false], false],
            [0, ['totAuthVehicles' => 0, 'isVariation' => false], false],
            [0, ['totAuthVehicles' => 2, 'isVariation' => true], true],
            [6, ['totAuthVehicles' => 5, 'isVariation' => false], false],
            [6, ['totAuthVehicles' => 5, 'isVariation' => false], false],
            [4, ['totAuthVehicles' => 5, 'isVariation' => false], true],
        ];
    }
}
