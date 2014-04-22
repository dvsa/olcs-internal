<?php

/**
 * Test CaseAppealController
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */

namespace OlcsTest\Controller;

/**
 * Test CaseAppealController
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class CaseAppealControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test addAction
     */
    public function testAddAction()
    {
        $caseId = 7;
        $form = '<form></form>';

        $viewMock = $this->getMock('\stdClass', array('setTemplate'));

        $viewMock->expects($this->once())
            ->method('setTemplate')
            ->with('form');

        $controller = $this->getMock(
            'Olcs\Controller\CaseAppealController',
            array('fromRoute', 'generateFormWithData', 'getView')
        );

        $controller->expects($this->once())
            ->method('fromRoute')
            ->with('case')
            ->will($this->returnValue($caseId));

        $controller->expects($this->once())
            ->method('generateFormWithData')
            ->with('appeal', 'processAddAppeal', array('case' => $caseId))
            ->will($this->returnValue($form));

        $controller->expects($this->once())
            ->method('getView')
            ->will($this->returnValue($viewMock));

        $this->assertEquals($viewMock, $controller->addAction());
    }

    /**
     * Test editAction with missing appeal
     */
    public function testEditActionWithMissingAppeal()
    {
        $controller = $this->getMock(
            'Olcs\Controller\CaseAppealController',
            array('fromRoute', 'makeRestCall', 'notFoundAction')
        );

        $controller->expects($this->once())
            ->method('fromRoute')
            ->will($this->returnValue(3));

        $controller->expects($this->once())
            ->method('makeRestCall')
            ->with('Appeal')
            ->will($this->returnValue(false));

        $controller->expects($this->once())
            ->method('notFoundAction')
            ->will($this->returnValue('404'));

        $this->assertEquals('404', $controller->editAction());
    }

    /**
     * Test editAction
     */
    public function testEditAction()
    {
        $appealId = 7;

        $appealDetails = array(
            'reason' => '5',
            'outcome' => '9'
        );

        $form = '<form></form>';

        $expectedData = array(
            'reason' => '5',
            'outcome' => '9',
            'details' => array(
                'reason' => 'appeal_reason.5',
                'outcome' => 'appeal_outcome.9'
            )
        );

        $viewMock = $this->getMock('\stdClass', array('setTemplate'));

        $viewMock->expects($this->once())
            ->method('setTemplate')
            ->with('form');

        $controller = $this->getMock(
            'Olcs\Controller\CaseAppealController',
            array('fromRoute', 'makeRestCall', 'generateFormWithData', 'getView')
        );

        $controller->expects($this->once())
            ->method('fromRoute')
            ->will($this->returnValue(3));

        $controller->expects($this->once())
            ->method('makeRestCall')
            ->with('Appeal')
            ->will($this->returnValue($appealDetails));

        $controller->expects($this->once())
            ->method('generateFormWithData')
            ->with('appeal', 'processEditAppeal', $expectedData)
            ->will($this->returnValue($form));

        $controller->expects($this->once())
            ->method('getView')
            ->will($this->returnValue($viewMock));

        $this->assertEquals($viewMock, $controller->editAction());
    }

    /**
     * Test processAddAppeal
     */
    public function testProcessAddAppeal()
    {
        $data = array(
            'case' => 9,
            'details' => array(
                'reason' => 'appeal_reason.6',
                'outcome' => 'appeal_outcome.3'
            )
        );

        $expectedProcessedData = array(
            'case' => 9,
            'reason' => 6,
            'outcome' => 3
        );

        $mockRedirect = $this->getMock('\stdClass', array('toRoute'));

        $mockRedirect->expects($this->once())
            ->method('toRoute')
            ->will($this->returnValue('redirect'));

        $controller = $this->getMock(
            'Olcs\Controller\CaseAppealController',
            array('makeRestCall', 'redirect')
        );

        $controller->expects($this->once())
            ->method('makeRestCall', $expectedProcessedData)
            ->with('Appeal', 'POST');

        $controller->expects($this->once())
            ->method('redirect')
            ->will($this->returnValue($mockRedirect));

        $controller->processAddAppeal($data);
    }

    /**
     * Test processEditAppeal
     */
    public function testProcessEditAppeal()
    {
        $data = array(
            'case' => 9,
            'details' => array(
                'reason' => 'appeal_reason.6',
                'outcome' => 'appeal_outcome.3'
            )
        );

        $expectedProcessedData = array(
            'case' => 9,
            'reason' => 6,
            'outcome' => 3
        );

        $mockRedirect = $this->getMock('\stdClass', array('toRoute'));

        $mockRedirect->expects($this->once())
            ->method('toRoute')
            ->will($this->returnValue('redirect'));

        $controller = $this->getMock(
            'Olcs\Controller\CaseAppealController',
            array('makeRestCall', 'redirect')
        );

        $controller->expects($this->once())
            ->method('makeRestCall', $expectedProcessedData)
            ->with('Appeal', 'PUT');

        $controller->expects($this->once())
            ->method('redirect')
            ->will($this->returnValue($mockRedirect));

        $controller->processEditAppeal($data);
    }
}
