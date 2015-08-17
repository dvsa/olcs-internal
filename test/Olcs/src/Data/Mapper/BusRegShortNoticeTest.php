<?php
namespace OlcsTest\Data\Mapper;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Olcs\Data\Mapper\BusRegShortNotice as Sut;
use Zend\Form\FormInterface;
use Common\RefData;

/**
 * BusRegShortNotice Mapper Test
 */
class BusRegShortNoticeTest extends MockeryTestCase
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
                []
            ],
            // edit
            [
                [
                    'result' => [
                        [
                            'id' => 987,
                            'busReg' => [
                                'status' => ['id' => RefData::BUSREG_STATUS_NEW],
                            ]
                        ]
                    ]
                ],
                [
                    'fields' => [
                        'id' => 987,
                        'busReg' => [
                            'status' => ['id' => RefData::BUSREG_STATUS_NEW],
                        ],
                        'busRegStatus' => RefData::BUSREG_STATUS_NEW,
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
