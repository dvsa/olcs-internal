<?php

namespace OlcsTest\View\Helper;

use Olcs\View\Helper\Markers;
use Zend\View\Model\ViewModel;
use Mockery as m;

/**
 * Class MarkersTest
 * @package OlcsTest\View\Helper
 */
class MarkersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideInvoke
     * @param $input
     * @param $expected
     */
    public function testInvoke($input, $expected)
    {
        $sut = new Markers();

        $mockView = m::mock('\Zend\View\Renderer\PhpRenderer');
        $mockViewHelper = m::mock('\Zend\View\Helper\Url');
        $mockViewHelper->shouldReceive('__invoke');
        $mockView->shouldReceive('plugin')->andReturn($mockViewHelper);

        $sut->setView($mockView);

        $result = $sut($input['markers'], $input['type']);
        if (isset($expected['count']) && $expected['count'] == 0) {
            $this->assertEquals($result, '');
        } else {
            // count individual markers
            $this->assertEquals($expected['count'], substr_count($result, 'notice--warning'));
        }

        if (isset($expected['contains'])) {
            $this->assertContains($expected['contains'], $result);
        }
    }

    public function provideInvoke()
    {
        return [
            [
                ['markers' => null, 'type' => null],
                ['count' => 0]
            ],
            [
                ['markers' => [], 'type' => ''],
                ['count' => 0]
            ],
            [
                [
                    'markers' =>
                        ['sometype' =>
                            [
                                0 => ['content' => 'foo']
                            ]
                        ],
                    'type' => 'sometype'
                ],
                ['count' => 1, 'contains' => 'foo'],
            ],
            [
                [
                    'markers' =>
                        ['sometype' =>
                            [
                                0 => ['content' => 'bar'],
                                1 => ['content' => 'bar'],
                                2 => ['content' => 'bar']
                            ]
                        ],
                    'type' => 'sometype'
                ],
                ['count' => 3, 'contains' => 'bar'],
            ]
        ];
    }
}
