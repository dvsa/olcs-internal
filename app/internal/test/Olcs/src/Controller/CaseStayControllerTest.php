<?php

/**
 * Case Stay Test Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */

namespace OlcsTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class CaseStayControllerTest extends AbstractHttpControllerTestCase
{

    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../config/application.config.php'
        );

        $this->controller = $this->getMock(
            '\Olcs\Controller\CaseStayController',
            [
                'makeRestCall',
                'fromRoute',
                'getCase',
                'generateFormWithData',
                'getCaseVariables',
                'notFoundAction',
                'redirect',
                'processAdd',
                'processEdit',
                'url',
                'getServiceLocator',
                'setBreadcrumb'
            ]
        );

        $this->view = $this->getMock(
            'Zend\View\Model\ViewModel',
            [
                'setVariables',
                'setTemplate'
            ]
        );

        $this->pm = $this->getMock('\stdClass', array('get'));

        parent::setUp();
    }

    /**
     * Tests the indexAction
     *
     * @dataProvider indexActionProvider
     *
     * @param int $caseId
     * @param array $searchResults
     *
     */
    public function testIndexAction($licenceId, $caseId)
    {
        $mockService = $this->getServiceLocatorMock();
        $mockUrl = $this->getUrlMock();

        $this->getFromRoute(0, 'case', $caseId);
        $this->getFromRoute(1, 'licence', $licenceId);

        $this->controller->expects($this->once())
            ->method('setBreadcrumb');

        $this->controller->expects($this->once())
            ->method('getServiceLocator')
            ->will($this->returnValue($mockService));

        $this->controller->expects($this->any())
            ->method('url')
            ->will($this->returnValue($mockUrl));

        $this->controller->expects($this->exactly(2))
            ->method('makeRestCall')
            ->will($this->onConsecutiveCalls($this->getStayRestResult(1), $this->getAppealRestResult()));

        $this->controller->expects($this->once())
            ->method('getCaseVariables');

        $this->controller->indexAction();
    }

    /**
     * Tests indexAction if a none numeric case id is posted
     *
     * @dataProvider badIdProvider
     *
     * @param int $caseId
     */
    public function testIndexActionNotFound($caseId)
    {
        $this->getFromRoute(0, 'case', $caseId);

        $this->controller->expects($this->once())
            ->method('notFoundAction');

        $this->controller->indexAction();
    }

    /**
     * Tests the add action
     *
     * @dataProvider addActionProvider
     *
     * @param int $caseId
     * @param int $stayTypeId
     */
    public function testAddAction($caseId, $stayTypeId, $licenceId)
    {
        $this->getFromRoute(0, 'licence', $licenceId);
        $this->getFromRoute(1, 'case', $caseId);

        $this->controller->expects($this->at(2))
            ->method('getCase')
            ->with($this->equalTo($caseId))
            ->will($this->returnValue(array('data' => 'data')));

        $this->getFromRoute(3, 'stayType', $stayTypeId);

        $this->controller->expects($this->once())
            ->method('makeRestCall')
            ->with('Stay', 'GET', $this->equalTo(array('case' => $caseId)))
            ->will($this->returnValue($this->getEmptyStayRestResult()));

        $this->controller->expects($this->once())
            ->method('setBreadcrumb');

        $this->controller->expects($this->once())
            ->method('generateFormWithData');

        $this->controller->expects($this->never())
            ->method('notFoundAction');

        $this->controller->addAction();
    }

    /**
     * Tests the add action fails when a record already exists
     *
     * @dataProvider addActionProvider
     *
     * @param int $caseId
     * @param int $stayTypeId
     *
     */
    public function testAddActionFailExists($caseId, $stayTypeId, $licenceId)
    {
        $redirectInfo = $this->getRedirectSuccess($caseId, $licenceId);
        $redirect = $this->getRedirectMock($redirectInfo);

        $this->getFromRoute(0, 'licence', $licenceId);
        $this->getFromRoute(1, 'case', $caseId);

        $this->controller->expects($this->at(2))
            ->method('getCase')
            ->with($this->equalTo($caseId))
            ->will($this->returnValue(array('data' => 'data')));

        $this->getFromRoute(3, 'stayType', $stayTypeId);

        $this->controller->expects($this->at(4))
            ->method('makeRestCall')
            ->with('Stay', 'GET', $this->equalTo(array('case' => $caseId)))
            ->will($this->returnValue($this->getStayRestResult($stayTypeId)));

        $this->controller->expects($this->at(5))
            ->method('redirect')
            ->will($this->returnValue($redirect));

        $this->controller->addAction();
    }

    /**
     * Tests the add action fails when no case data is found
     */
    public function testAddActionNotFoundCase()
    {
        $this->getFromRoute(0, 'licence', false);
        $this->getFromRoute(1, 'case', false);

        $this->controller->expects($this->at(2))
            ->method('getCase')
            ->will($this->returnValue(''));

        $this->controller->expects($this->at(3))
            ->method('notFoundAction');

        $this->controller->addAction();
    }

    /**
     * Tests the add action fails when no stay type data is found
     *
     * @dataProvider badIdProvider
     */
    public function testAddActionNotFoundStayType($stayTypeId)
    {
        $this->getFromRoute(0, 'licence', false);
        $this->getFromRoute(1, 'case', false);

        $this->controller->expects($this->at(2))
            ->method('getCase')
            ->will($this->returnValue(array('data' => 'data')));

        $this->getFromRoute(3, 'stayType', $stayTypeId);

        $this->controller->expects($this->once())
            ->method('notFoundAction');

        $this->controller->addAction();
    }

    /**
     * Tests the edit action fails if the stay id is missing
     */
    public function testEditActionFailStay()
    {
        $this->getFromRoute(0, 'stay', false);

        $this->controller->expects($this->at(1))
            ->method('makeRestCall')
            ->will($this->returnValue(''));

        $this->controller->expects($this->at(2))
            ->method('notFoundAction');

        $this->controller->editAction();
    }

    /**
     * Tests the edit action fails if the case id is missing
     */
    public function testEditActionFailCase()
    {
        $this->getFromRoute(0, 'stay', false);

        $this->controller->expects($this->at(1))
            ->method('makeRestCall')
            ->will($this->returnValue(array('data' => 'data')));

        $this->getFromRoute(2, 'case', false);

        $this->controller->expects($this->once())
            ->method('getCase')
            ->will($this->returnValue(''));

        $this->controller->editAction();
    }

    /**
     * Tests the edit action fails when no stay type data is found
     *
     * @dataProvider badIdProvider
     */
    public function testEditActionFailStayType($stayTypeId)
    {
        $this->getFromRoute(0, 'stay', false);

        $this->controller->expects($this->once())
            ->method('makeRestCall')
            ->will($this->returnValue(array('data' => 'data')));

        $this->getFromRoute(2, 'case', false);

        $this->controller->expects($this->once())
            ->method('getCase')
            ->will($this->returnValue(array('data' => 'data')));

        $this->getFromRoute(4, 'licence', false);
        $this->getFromRoute(5, 'stayType', $stayTypeId);

        $this->controller->expects($this->once())
            ->method('notFoundAction');

        $this->controller->editAction();
    }

    /**
     * Tests the edit action
     *
     * @dataProvider editActionProvider
     *
     * @param int $caseId
     * @param int $stayTypeId
     * @param int $stayId
     */
    public function testEditAction($caseId, $stayTypeId, $stayId, $licenceId)
    {
        $restEnd = 'Stay';
        $restComm = 'GET';
        $restParam = array('id' => $stayId);

        $this->getFromRoute(0, 'stay', $stayId);

        $this->controller->expects($this->at(1))
            ->method('makeRestCall')
            ->with($this->equalTo($restEnd), $this->equalTo($restComm), $this->equalTo($restParam))
            ->will($this->returnValue(array('data' => 'data')));

        $this->getFromRoute(2, 'case', $caseId);

        $this->controller->expects($this->at(3))
            ->method('getCase')
            ->with($this->equalTo($caseId))
            ->will($this->returnValue(array('data' => 'data')));

        $this->getFromRoute(4, 'licence', $licenceId);
        $this->getFromRoute(5, 'stayType', $stayTypeId);

        $this->controller->expects($this->once())
            ->method('generateFormWithData');

        $this->controller->expects($this->once())
            ->method('setBreadcrumb');

        $this->controller->editAction();
    }

    /**
     * Tests processAddStay
     *
     * @dataProvider processAddStayProvider
     *
     * @param array $data
     *
     */
    public function testProcessAddStayFailExists($data)
    {
        $redirectInfo = $this->getRedirectExistsFail($data['case'], $data['licence']);
        $redirect = $this->getRedirectMock($redirectInfo);

        $this->controller->expects($this->once())
            ->method('makeRestCall')
            ->with('Stay', 'GET', $this->equalTo(array('case' => $data['case'])))
            ->will($this->returnValue($this->getStayRestResult($data['stayType'])));

        $this->controller->expects($this->once())
            ->method('redirect')
            ->will($this->returnValue($redirect));

        $this->controller->processAddStay($data);
    }

    /**
     * Tests processAddStay
     *
     * @dataProvider processAddStayProvider
     *
     * @param array $data
     *
     */
    public function testProcessAddStay($data)
    {
        $this->controller->expects($this->once())
            ->method('makeRestCall')
            ->with('Stay', 'GET', $this->equalTo(array('case' => $data['case'])))
            ->will($this->returnValue($this->getEmptyStayRestResult()));

        $this->controller->expects($this->at(1))
            ->method('processAdd')
            ->will($this->returnValue(array('id' => 1)));

        $redirectInfo = $this->getRedirectSuccess($data['case'], $data['licence']);
        $redirect = $this->getRedirectMock($redirectInfo);

        $this->controller->expects($this->once())
            ->method('redirect')
            ->will($this->returnValue($redirect));

        $this->controller->processAddStay($data);
    }

    /**
     * Tests processAddStay
     *
     * @dataProvider processAddStayProvider
     *
     * @param array $data
     *
     */
    public function testProcessAddStayFail($data)
    {
        $this->controller->expects($this->once())
            ->method('makeRestCall')
            ->with('Stay', 'GET', $this->equalTo(array('case' => $data['case'])))
            ->will($this->returnValue($this->getEmptyStayRestResult()));

        $this->controller->expects($this->at(1))
            ->method('processAdd')
            ->will($this->returnValue(array()));

        $redirectInfo = $this->getRedirectAddFail($data['case'], $data['stayType'], $data['licence']);
        $redirect = $this->getRedirectMock($redirectInfo);

        $this->controller->expects($this->once())
            ->method('redirect')
            ->will($this->returnValue($redirect));

        $this->controller->processAddStay($data);
    }

    /**
     * Tests processEditStay
     *
     * @dataProvider processEditStayProvider
     *
     * @param array $data
     *
     */
    public function testProcessEditStay($data)
    {
        $this->controller->expects($this->at(0))
            ->method('processEdit')
            ->will($this->returnValue(''));

        $redirectInfo = $this->getRedirectSuccess($data['case'], $data['licence']);
        $redirect = $this->getRedirectMock($redirectInfo);

        $this->controller->expects($this->once())
            ->method('redirect')
            ->will($this->returnValue($redirect));

        $this->controller->processEditStay($data);
    }

    /**
     * Tests for failure of processEditStay
     *
     * @dataProvider processEditStayProvider
     *
     * @param array $result
     * @param array $data
     *
     */
    public function testProcessEditStayFail($data)
    {
        $this->controller->expects($this->at(0))
            ->method('processEdit')
            ->will($this->returnValue(array('data' => 'data')));

        $redirectInfo = $this->getRedirectEditFail($data['case'], $data['stay'], $data['stayType'], $data['licence']);
        $redirect = $this->getRedirectMock($redirectInfo);

        $this->controller->expects($this->once())
            ->method('redirect')
            ->will($this->returnValue($redirect));

        $this->controller->processEditStay($data);
    }

    /**
     *
     * @dataProvider getStayDataWithResultsProvider
     *
     * @param int $licenceId
     * @param int $caseId
     * @param array $static
     */
    public function atestGetStayDataWithResults($licenceId, $caseId, $static)
    {
        $this->controller->expects($this->at(0))
            ->method('makeRestCall')
            ->with('Stay', 'GET', $this->equalTo(array('case' => $caseId)))
            ->will($this->returnValue($this->getStayRestResult(1)));

        $this->controller->expects($this->atLeastOnce())
            ->method('formatDates');

        $this->controller->expects($this->atLeastOnce())
            ->method('getUrl');

        $this->controller->getStayData($licenceId, $caseId, $static);
    }

    /**
     * Data provider for testGetStayWithResults
     *
     * @return array
     */
    public function getStayDataWithResultsProvider()
    {
        return array(
            array(7, 24, $this->getStaticStayData())
        );
    }

    /**
     * Data provider for testEditAction
     *
     * @return array
     */
    public function editActionProvider()
    {
        return array(
            array(24, 1, 1, 7),
            array(24, 1, 2, 7),
        );
    }

    /**
     * Data provider for testAddAction
     *
     * @return array
     */
    public function addActionProvider()
    {
        return array(
            array(24, 1, 7),
            array(24, 2, 7),
            array(28, 1, 7),
            array(28, 2, 7)
        );
    }

    /**
     * Data provider for index action
     *
     * @return array
     */
    public function indexActionProvider()
    {
        return array(
            array(7, 24),
            array(7, 24),
        );
    }

    /**
     * Returns a list of Ids which should fail validation
     *
     * @return array
     */
    public function badIdProvider()
    {
        return array(
            array(0),
            array(''),
            array('aaa')
        );
    }

    public function getAppealRestResult()
    {
        return array(
            'Results' => array(
                0 => array(
                    'id' => 1,
                    'outcome' => 1,
                    'reason' => 1,
                    'deadlineDate' => '',
                    'appealDate' => '',
                    'hearingDate' => '',
                    'decisionDate' => '',
                    'papersDue' => '',
                    'papersSent' => ''
                )
            )
        );
    }

    /**
     * simulates a stay rest result array with results
     *
     * @param int $stayTypeId
     * @return array
     */
    public function getStayRestResult($stayTypeId)
    {
        return array('Results' => array(0 => array('id' => 1,'stayType' => $stayTypeId, 'outcome' => 'stay_status_granted', 'requestDate' => strtotime(time()))));
    }

    public function getStaticStayData()
    {
        return array(
            1 => 'outcome 1',
            2 => 'outcome 2',
            3 => 'outcome 3',
            4 => 'outcome 4',
        );
    }

    /**
     * simulates a stay rest result array with no results
     *
     * @return array
     */
    public function getEmptyStayRestResult()
    {
        return array('Results' => array());
    }

    /**
     * Data provider for testProcessAddStay
     *
     * @return array
     */
    public function processAddStayProvider()
    {
        return array(
            array(
                array('case' => 1, 'stayType' => 1, 'licence' => 7, 'fields' => array()),
                array('case' => 1, 'stayType' => 2, 'licence' => 7, 'fields' => array())
            ),
        );
    }

    /**
     * Data provider for testProcessEditStay
     *
     * @return array
     */
    public function processEditStayProvider()
    {
        return array(
            array(array('case' => 1, 'stay' => 1, 'stayType' => 1, 'licence' => 7, 'fields' => array())),
            array(array('case' => 1, 'stay' => 1, 'stayType' => 2, 'licence' => 7, 'fields' => array())),
        );
    }

    /**
     * Parameters for a successful redirect
     *
     * @param int $caseId
     * @param int $licenceId
     * @return array
     */
    private function getRedirectSuccess($caseId, $licenceId)
    {
        $redirectInfo['string'] = $this->getRedirectAction();
        $redirectInfo['options'] = array('action' => 'index', 'case' => $caseId, 'licence' => $licenceId);
        return $redirectInfo;
    }

    /**
     * Parameters for redirect if adding a record failed
     *
     * @param int $caseId
     * @param int $stayTypeId
     * @param int $licenceId
     * @return array
     */
    private function getRedirectAddFail($caseId, $stayTypeId, $licenceId)
    {
        $redirectInfo['string'] = $this->getRedirectAction();
        $redirectInfo['options'] = array('action' => 'add', 'case' => $caseId, 'stayType' => $stayTypeId, 'licence' => $licenceId);
        return $redirectInfo;
    }

    /**
     * Parameters for a redirect after a failure where a record already existed
     * This is an alias for getRedirectSuccess as they do the same thing
     *
     * @param int $caseId
     * @param int $licenceId
     * @return array
     */
    private function getRedirectExistsFail($caseId, $licenceId)
    {
        return $this->getRedirectSuccess($caseId, $licenceId);
    }

    /**
     * Paramaeters for a redirect after an edit has failed
     *
     * @param int $caseId
     * @param int $stayId
     * @param int $stayTypeId
     * @param int $licenceId
     *
     * @return array
     */
    private function getRedirectEditFail($caseId, $stayId, $stayTypeId, $licenceId)
    {
        $redirectInfo['string'] = $this->getRedirectAction();
        $redirectInfo['options'] = array('action' => 'edit', 'case' => $caseId, 'stayType' => $stayTypeId, 'stay' => $stayId, 'licence' => $licenceId);
        return $redirectInfo;
    }

    /**
     * Returns the routing action for the stays page
     *
     * @return string
     */
    private function getRedirectAction()
    {
        return 'case_stay_action';
    }

    /**
     * Creates a mock class (used for the redirect method)
     *
     * @param array $redirectInfo
     * @return type
     */
    private function getRedirectMock($redirectInfo)
    {
        $redirect = $this->getMock('stdClass', ['toRoute']);
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with($this->equalTo($redirectInfo['string']), $this->equalTo($redirectInfo['options']));

        return $redirect;
    }

    /**
     * Creates a mock url function
     *
     * @return type
     */
    private function getUrlMock()
    {
        return $this->getMock('\stdClass', array('fromRoute'));
    }

    /**
     * Generate a fromRoute function call
     *
     * @param int $at
     * @param mixed $with
     * @param mixed $will
     */
    private function getFromRoute($at, $with, $will = false)
    {
        if ($will) {
            $this->controller->expects($this->at($at))
                ->method('fromRoute')
                ->with($this->equalTo($with))
                ->will($this->returnValue($will));
        } else {
            $this->controller->expects($this->at($at))
                ->method('fromRoute')
                ->with($this->equalTo($with));
        }
    }

    /**
     * Creates a mock service locator
     *
     * @return type
     */
    private function getServiceLocatorMock()
    {
        $service = $this->getMock('\stdClass', array('get'));
        $service->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->getConfOutcomes()));

        return $service;
    }

    /**
     * Data for outcomes
     *
     * @return array
     */
    private function getConfOutcomes()
    {
        return array(
            'static-list-data' => array(
                'case_stay_outcome' => [
                    'stay_status_granted' => 'Granted',
                    'stay_status_refused' => 'Refused'
                ],
                'appeal_reasons' => [
                    'appeal_reason.1' => 'Application',
                    'appeal_reason.2' => 'Disciplinary PI',
                    'appeal_reason.3' => 'Disciplinary Non PI',
                    'appeal_reason.4' => 'Impounding'
                ],
                'appeal_outcomes' => [
                    'appeal_outcome.1' => 'Successful',
                    'appeal_outcome.2' => 'Partially Successful',
                    'appeal_outcome.3' => 'Dismissed',
                    'appeal_outcome.4' => 'Refer back to TC'
                ]
            )
        );
    }
}
