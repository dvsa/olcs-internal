<?php
namespace OlcsTest\Data\Mapper;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Olcs\Data\Mapper\Recipient as Sut;
use Laminas\Form\FormInterface;

/**
 * Recipient Mapper Test
 */
class RecipientTest extends MockeryTestCase
{
    /**
    * @dataProvider mapFromResultDataProvider
    *
    * @param $inData
    * @param $expected
    */
    public function testMapFromResult($inData, $expected)
    {
        $this->assertEquals($expected, Sut::mapFromResult($inData));
    }

    public function mapFromResultDataProvider()
    {
        return [
            // add
            [
                [],
                ['fields' => []]
            ],
            // edit
            [
                [
                    'id' => 987,
                    'organisation' => ['id' => 100],
                ],
                [
                    'fields' => [
                        'id' => 987,
                        'organisation' => 100,
                    ],
                ]
            ]
        ];
    }

    public function testMapFromForm()
    {
        $inData = ['fields' => ['field' => 'data']];
        $expected = ['field' => 'data'];

        $this->assertEquals($expected, Sut::mapFromForm($inData));
    }

    public function testMapFromErrors()
    {
        $mockForm = m::mock(FormInterface::class);
        $errors = ['field' => 'data'];

        $this->assertEquals($errors, Sut::mapFromErrors($mockForm, $errors));
    }
}
