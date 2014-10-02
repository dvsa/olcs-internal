<?php


namespace OlcsTest\View\Helper;

use Olcs\View\Helper\SubmissionSectionOverview;
use Mockery as m;

/**
 * Class SubmissionSectionOverview
 * @package OlcsTest\View\Helper
 */
class SubmissionSectionOverviewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideInvoke
     * @param $input
     * @param $expected
     */
    public function testInvoke($input, $expected)
    {
        $sut = new SubmissionSectionOverview();

        $mockView = m::mock('\Zend\View\Renderer\PhpRenderer');

        $mockViewHelper = m::mock('Olcs\View\Helper\SubmissionSectionOverview');

        $mockViewHelper->shouldReceive('__invoke');
        $mockView->shouldReceive('plugin')->andReturn($mockViewHelper);
        $mockView->shouldReceive('render');

        $sut->setView($mockView);

        $this->assertEquals(
            $expected,
            $sut(
                $input['submissionSection'],
                $input['data']
            )
        );
    }

    /**
     * @dataProvider provideInvokeNotPluggable
     * @param $input
     * @param $expected
     */
    public function testInvokeNotPluggable($input, $expected)
    {
        $sut = new SubmissionSectionOverview();

        $mockView = m::mock('\Zend\View\Renderer\RendererInterface');

        $mockView->shouldReceive('render');

        $sut->setView($mockView);

        $this->assertEquals(
            $expected,
            $sut(
                $input['submissionSection'],
                $input['data']
            )
        );
    }

    public function provideInvoke()
    {
        return [
            [['submissionSection' => '', 'data' => []], ''],
            [['submissionSection' => [], 'data' => []], ''],
            [['submissionSection' => null, 'data' => []], ''],
            [['submissionSection' => false, 'data' => []], ''],
            [['submissionSection' => 'rubbish', 'data' => []], ''],
            [['submissionSection' => 'submission_section_intr', 'data' => []], ''],

        ];
    }

    public function provideInvokeNotPluggable()
    {
        return [
            [['submissionSection' => 'rubbish', 'data' => []], ''],
            [['submissionSection' => 'submission_section_intr', 'data' => []], '']
        ];
    }
}
