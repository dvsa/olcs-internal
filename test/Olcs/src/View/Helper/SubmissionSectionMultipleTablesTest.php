<?php


namespace OlcsTest\View\Helper;

use Olcs\View\Helper\SubmissionSectionTable;
use Olcs\View\Helper\SubmissionSectionMultipleTables;
use Olcs\View\Helper\SubmissionSectionTableFactory;
use Mockery as m;

/**
 * Class SubmissionSectionMultipleTables
 * @package OlcsTest\View\Helper
 */
class SubmissionSectionMultipleTablesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideInvoke
     * @param $input
     * @param $expected
     */
    public function testInvoke($input, $expected)
    {
        $sut = new SubmissionSectionMultipleTables();

        $mockView = m::mock('\Zend\View\Renderer\PhpRenderer');

        $mockViewHelper = m::mock('Olcs\View\Helper\SubmissionSectionMultipleTables');
        $mockTableHelper = m::mock('Olcs\View\Helper\SubmissionSectionTable');
        $mockTableHelper->shouldReceive('__invoke')->andReturn('<table></table>');

        $mockViewHelper->shouldReceive('__invoke');
        $mockView->shouldReceive('plugin')->andReturn($mockViewHelper);
        $mockView->shouldReceive('SubmissionSectionTable')->andReturn($mockTableHelper);
        $mockView->shouldReceive('render');

        $sut->setView($mockView);

        $result = $sut(
            $input['submissionSection'],
            $input['data']
        );

        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function provideInvoke()
    {
        return [
            [
                ['submissionSection' => 'introduction', 'data' => ['data' => []]], null
            ],
            [
                ['submissionSection' => '', 'data' => ['data' => []]], ''
            ],
            [
                [
                    'submissionSection' => 'condition-and-undertakings',
                    'data' => [
                        'data' => [
                            'tables' => [
                                'conditions' => [
                                    0 => ['id' => 1],
                                    1 => ['id' => 2]
                                ]
                            ]
                        ]
                    ]
                ],
                null
            ],
        ];
    }
}
