<?php

/**
 * Appeal Test Controller
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */

namespace OlcsTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Olcs\Controller\Cases\Hearing\StayController;
use OlcsTest\Controller\ControllerTestAbstract;

/**
 * Appeal Test Controller
 */
class AppealControllerTest extends ControllerTestAbstract
{
    protected $testClass = 'Olcs\Controller\Cases\Hearing\AppealController';

    protected $proxyMethdods = [
        'indexAction' => 'redirectToIndex'
    ];

    /**
     * Isolated test for the redirect action method.
     */
    public function testRedirectToIndex()
    {
        $sut = $this->getMock($this->testClass, ['redirectToRoute']);
        $sut->expects($this->once())
            ->method('redirectToRoute')
            ->with('case_hearing_appeal', ['action' => 'details'], [], true)
            ->will($this->returnValue('return'));

        $this->assertEquals('return', $sut->redirectToIndex());
    }
}
