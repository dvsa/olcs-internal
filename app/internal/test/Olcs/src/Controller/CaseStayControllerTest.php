<?php

/**
 * Case Stay Test Controller
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 * @todo better unit tests
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

        parent::setUp();
    }

    /**
     * @dataProvider caseIdProvider
     */
    public function testIndexAction($caseId)
    {
        $response = $this->dispatch('/case/24/action/manage/stays', 'GET', array('action' => 'index', 'case' => $caseId));
        $this->assertResponseStatusCode(200);
        $this->assertControllerName('casestaycontroller');
        $this->assertControllerClass('CaseStayController');
        $this->assertMatchedRouteName('case_stay_action');
        $this->assertActionName('index');

        $restEnd = 'Stay';
        $restComm = 'GET';
        $restParam = array('case' => $caseId);

        $sut = $this->getMock(
            '\Olcs\Controller\CaseStayController', ['getStayTypes', 'fromRoute', 'makeRestCall', 'getCaseVariables']
        );

        $view = $this->getMock(
            'Zend\View\Model\ViewModel', ['setVariables', 'setTemplate']
        );

        $sut->expects($this->once())
            ->method('getStayTypes');

        $sut->expects($this->once())
            ->method('fromRoute')
            ->with($this->equalTo('case'))
            ->will($this->returnValue($this->caseProvider($caseId)));

        $sut->expects($this->once())
            ->method('makeRestCall');

        $sut->expects($this->once())
            ->method('getCaseVariables');

        $sut->indexAction();
    }

    public function getEmptyRestResult($empty = true)
    {
        if ($empty) {
            return array('Results' => array());
        }

        return array('Results' => array([1] => 'test'));
    }

    /**
     * @dataProvider addActionProvider
     */
    public function testAddAction($caseId, $stayTypeId, $existingRecord)
    {
        $response = $this->dispatch('/case/24/action/manage/stays/add/1', 'GET', array('action' => 'add', 'case' => $caseId, 'stayType' => $stayTypeId));

        $this->assertControllerName('casestaycontroller');
        $this->assertControllerClass('CaseStayController');
        $this->assertMatchedRouteName('case_stay_action');
        $this->assertActionName('add');
        $case = ['key' => 'case'];
        $viewTemplate = 'case/add-stay';

        $view = $this->getMock(
            'Zend\View\Model\ViewModel', ['setVariables', 'setTemplate']
        );

        $sut = $this->getMock('\Olcs\Controller\CaseStayController', ['fromRoute', 'checkExistingStay', 'getCase', 'generateFormWithData', 'setTemplate', 'notFoundAction', 'getPageHeading', 'redirect']);

        $sut->expects($this->at(0))
            ->method('fromRoute')
            ->with($this->equalTo('case'))
            ->will($this->returnValue($caseId));

        $sut->expects($this->at(1))
            ->method('getCase')
            ->with($this->equalTo($caseId))
            ->will($this->returnValue($this->caseProvider($caseId)));

        if (empty($this->caseProvider($caseId))) {
            $sut->expects($this->at(2))
                ->method('notFoundAction');
        } else {
            $sut->expects($this->at(2))
                ->method('fromRoute')
                ->with($this->equalTo('stayType'))
                ->will($this->returnValue($stayTypeId));

            $sut->expects($this->at(3))
                ->method('checkExistingStay')
                ->with($this->equalTo($caseId), $this->equalTo($stayTypeId))
                ->will($this->returnValue($existingRecord));

            if ($existingRecord) {
                //$this->assertResponseStatusCode(302);
                $redirectInfo = $this->getRedirectSuccess($caseId);
                $redirect = $this->getRedirectMock($redirectInfo);

                $sut->expects($this->once())
                    ->method('redirect')
                    ->will($this->returnValue($redirect));
            } else {
                //$this->assertResponseStatusCode(200);
                $sut->expects($this->at(4))
                    ->method('generateFormWithData');

                $sut->expects($this->at(5))
                    ->method('getPageHeading')
                    ->with($this->equalTo('Add'), $this->equalTo($stayTypeId))
                    ->will($this->returnValue($this->getPageHeadingSuccess($stayTypeId)));

                if ($this->getPageHeadingSuccess($stayTypeId)) {
                    $sut->expects($this->never())
                        ->method('notFoundAction');
                } else {
                    //$view->expects($this->at(0))
                    //->method('setTemplate')
                    //->with($this->equalTo($viewTemplate));
                }
            }
        }

        $sut->addAction();
    }

    /**
     * @dataProvider editActionProvider
     */
    public function testEditAction($caseId, $stayTypeId, $stayId)
    {
        //$response = $this->dispatch('/case/24/action/manage/stays/edit/1/1', 'GET', array('action' => 'edit', 'case' => $caseId, 'stayType' => $stayTypeId, 'stay' => $stayId));
        //$this->assertResponseStatusCode(200);
        //$this->assertControllerName('casestaycontroller');
        //$this->assertControllerClass('CaseStayController');
        //$this->assertMatchedRouteName('case_stay_action');
        //$this->assertActionName('edit');

        $restEnd = 'Stay';
        $restComm = 'GET';
        $restParam = array('id' => $stayId);
        $viewTemplate = 'case/add-stay';

        $sut = $this->getMock(
            '\Olcs\Controller\CaseStayController', ['fromRoute', 'makeRestCall', 'getCase', 'generateFormWithData', 'notFoundAction', 'setTemplate', 'getPageHeading']
        );

        $view = $this->getMock(
            'Zend\View\Model\ViewModel', ['setVariables', 'setTemplate']
        );

        $sut->expects($this->at(0))
            ->method('fromRoute')
            ->with($this->equalTo('stay'))
            ->will($this->returnValue($stayId));

        $sut->expects($this->at(1))
            ->method('makeRestCall')
            ->with($this->equalTo($restEnd), $this->equalTo($restComm), $this->equalTo($restParam))
            ->will($this->returnValue($this->stayProvider($stayId)));

        if (empty($this->stayProvider($stayId))) {
            $sut->expects($this->at(2))
                ->method('notFoundAction');
        } else {
            $sut->expects($this->at(2))
                ->method('fromRoute')
                ->with($this->equalTo('case'))
                ->will($this->returnValue($caseId));

            $sut->expects($this->at(3))
                ->method('getCase')
                ->with($this->equalTo($caseId))
                ->will($this->returnValue($this->caseProvider($caseId)));

            if (empty($this->caseProvider($caseId))) {
                $sut->expects($this->at(4))
                    ->method('notFoundAction');
            } else {
                $sut->expects($this->at(4))
                    ->method('fromRoute')
                    ->with($this->equalTo('stayType'))
                    ->will($this->returnValue($stayTypeId));

                $sut->expects($this->at(5))
                    ->method('generateFormWithData');

                $sut->expects($this->at(6))
                    ->method('getPageHeading')
                    ->with($this->equalTo('Edit'), $this->equalTo($stayTypeId))
                    ->will($this->returnValue($this->getPageHeadingSuccess($stayTypeId)));

                if ($this->getPageHeadingSuccess($stayTypeId)) {
                    $sut->expects($this->never())
                        ->method('notFoundAction');
                } else {
                    //$view->expects($this->at(0))
                    //->method('setTemplate')
                    //->with($this->equalTo($viewTemplate));
                }
            }
        }

        $sut->editAction();
    }

    /**
     * @dataProvider processAddStayProvider
     */
    public function testProcessAddStay($result, $data, $existingRecord)
    {
        $data['fields'] = array();

        $sut = $this->getMock(
            '\Olcs\Controller\CaseStayController', ['redirect', 'processAdd', 'checkExistingStay']
        );

        $sut->expects($this->at(0))
            ->method('checkExistingStay')
            ->with($this->equalTo($data['case']), $this->equalTo($data['stayType']))
            ->will($this->returnValue($existingRecord));

        if ($existingRecord) {
            //$this->assertResponseStatusCode(302);
            $redirectInfo = $this->getRedirectSuccess($data['case']);
            $redirect = $this->getRedirectMock($redirectInfo);

            $sut->expects($this->once())
                ->method('redirect')
                ->will($this->returnValue($redirect));
        } else {
            //$this->assertResponseStatusCode(200);
            $sut->expects($this->at(1))
                ->method('processAdd')
                ->will($this->returnValue($result));

            if ($this->getInsertSuccess($result)) {
                $redirectInfo = $this->getRedirectSuccess($data['case']);
            } else {
                $redirectInfo = $this->getRedirectAddFail($data['case'], $data['stayType']);
            }

            $redirect = $this->getRedirectMock($redirectInfo);

            $sut->expects($this->once())
                ->method('redirect')
                ->will($this->returnValue($redirect));
        }
        $sut->processAddStay($data);
    }

    /**
     * @dataProvider processEditStayProvider
     */
    public function testProcessEditStay($result, $data)
    {
        $data['fields'] = array();

        $sut = $this->getMock(
            '\Olcs\Controller\CaseStayController', ['redirect', 'processEdit']
        );

        $sut->expects($this->at(0))
            ->method('processEdit')
            ->will($this->returnValue($result));

        if ($this->getEditSuccess($result)) {
            $redirectInfo = $this->getRedirectSuccess($data['case']);
        } else {
            $redirectInfo = $this->getRedirectEditFail($data['case'], $data['stay'], $data['stayType']);
        }

        $redirect = $this->getRedirectMock($redirectInfo);

        $sut->expects($this->once())
            ->method('redirect')
            ->will($this->returnValue($redirect));

        return $sut->processEditStay($data);
    }

    /**
     * @dataProvider pageHeadingProvider
     */
    public function testGetPageHeading($action, $stayTypeId)
    {
        $sut = $this->getMock(
            '\Olcs\Controller\CaseStayController', ['getStayType']
        );

        $sut->expects($this->once())
            ->method('getStayType')
            ->with($this->equalTo($stayTypeId));

        $sut->getPageHeading($action, $stayTypeId);
    }

    /**
     * @dataProvider stayTypeIdProvider
     */
    public function testGetStayType($stayTypeId)
    {
        $sut = $this->getMock(
            '\Olcs\Controller\CaseStayController'
        );

        $sut->getStayType($stayTypeId);
    }

    /**
     * @dataProvider pageHeadingProvider
     */
    public function testCheckExistingStay($caseId, $stayTypeId)
    {
        $sut = $this->getMock(
            '\Olcs\Controller\CaseStayController', ['makeRestCall']
        );

        $sut->expects($this->once())
            ->method('makeRestCall');

        $sut->checkExistingStay($caseId, $stayTypeId);
    }

    public function checkExistingStayProvider()
    {
        return array(
            array(1, 1),
            array(1, 2),
            array(1, 0),
            array(0, 3)
        );
    }

    public function pageHeadingProvider()
    {
        return array(
            array('Add', 1),
            array('Add', 2),
            array('Edit', 1),
            array('Edit', 2),
            array('Add', 'aaa'),
            array('Edit', 'aaa'),
        );
    }

    /**
     * Returns a fake case object or null if input data is not int
     *
     * @param int $caseId
     * @return array|null
     */
    public function caseProvider($caseId)
    {
        if ((int) $caseId) {
            $case = ['key' => 'case'];
            return $case;
        }

        return null;
    }

    /**
     * Returns a fake stay object or null if input data is not int
     *
     * @param int $caseId
     * @return array|null
     */
    public function stayProvider($stayId)
    {
        if ((int) $stayId) {
            $stay = ['key' => 'stay'];
            return $stay;
        }

        return null;
    }

    /**
     * Data provider for testEditAction
     *
     * @return array
     */
    public function editActionProvider()
    {
        return array(
            array(24, 1, 1),
            array(24, 1, 2),
            array(24, 0, 10),
            array(24, 'a', 10),
            array(24, true, 10),
            array(24, 10, 10),
            array(0, 1, 0),
            array(0, 2, 0),
            array(24, 1, 0),
            array(24, 2, 0),
            array(0, 1, 10),
            array(0, 2, 10),
            array('', 1, ''),
            array('', 2, ''),
            array('a', 1, 'b'),
            array('a', 2, 'b'),
            array(false, 1, true),
            array(false, 2, true),
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
            array(24, 1, true),
            array(24, 2, true),
            array(24, 'a', true),
            array(24, true, true),
            array(24, 0, true),
            array(24, 10, true),
            array(0, 1, true),
            array(0, 2, true),
            array('', 1, true),
            array('', 2, true),
            array(false, 1, true),
            array(false, 2, true),
            array(true, 1, true),
            array(true, 2, true),
            array('a', 1, true),
            array('a', 2, true),
            array(24, 1, false),
            array(24, 2, false),
            array(24, 'a', false),
            array(24, true, false),
            array(24, 0, false),
            array(24, 10, false),
            array(0, 1, false),
            array(0, 2, false),
            array('', 1, false),
            array('', 2, false),
            array(false, 1, false),
            array(false, 2, false),
            array(true, 1, false),
            array(true, 2, false),
            array('a', 1, false),
            array('a', 2, false)
        );
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function stayTypeIdProvider()
    {
        return array(
            array(1),
            array(2),
            array(0),
            array(false),
            array(true),
            array('a')
        );
    }

    /**
     * Data provider for testAddAction
     *
     * @return array
     */
    public function caseIdProvider()
    {
        return array(
            array(24),
            array(0),
            array(''),
            array(false),
            array(true),
            array('a')
        );
    }

    /**
     * Data provider for testProcessAddStay
     *
     * @return array
     */
    public function processAddStayProvider()
    {
        return array(
            array(array('id' => 1), array('case' => 1, 'stayType' => 1), true),
            array(array('id' => 1), array('case' => 1, 'stayType' => 2), true),
            array(array(), array('case' => 1, 'stayType' => 2), true),
            array(array(), array('case' => 1, 'stayType' => 2), true),
            array(array('id' => 1), array('case' => 1, 'stayType' => 1), false),
            array(array('id' => 1), array('case' => 1, 'stayType' => 2), false),
            array(array(), array('case' => 1, 'stayType' => 2), false),
            array(array(), array('case' => 1, 'stayType' => 2), false)
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
            array(array('failed' => true), array('case' => 1, 'stay' => 1, 'stayType' => 1)),
            array(array('failed' => true), array('case' => 1, 'stay' => 1, 'stayType' => 2)),
            array(array(), array('case' => 1, 'stay' => 1, 'stayType' => 1)),
            array(array(), array('case' => 1, 'stay' => 1, 'stayType' => 2))
        );
    }

    public function getRedirectSuccess($caseId)
    {
        $redirectInfo['string'] = $this->getRedirectAction();
        $redirectInfo['options'] = array('action' => 'index', 'case' => $caseId);
        return $redirectInfo;
    }

    public function getRedirectAddFail($caseId, $stayTypeId)
    {
        $redirectInfo['string'] = $this->getRedirectAction();
        $redirectInfo['options'] = array('action' => 'add', 'case' => $caseId, 'stayType' => $stayTypeId);
        return $redirectInfo;
    }

    public function getRedirectEditFail($caseId, $stayId, $stayTypeId)
    {
        $redirectInfo['string'] = $this->getRedirectAction();
        $redirectInfo['options'] = array('action' => 'edit', 'case' => $caseId, 'stayType' => $stayTypeId, 'stay' => $stayId);
        return $redirectInfo;
    }

    public function getRedirectAction()
    {
        return 'case_stay_action';
    }

    public function getRedirectMock($redirectInfo)
    {
        $redirect = $this->getMock('stdClass', ['toRoute']);
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with($this->equalTo($redirectInfo['string']), $this->equalTo($redirectInfo['options']));

        return $redirect;
    }

    public function getInsertSuccess($result)
    {
        if (isset($result['id'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getEditSuccess($result)
    {
        if (empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * simulates whether page heading was generated successfully
     *
     * @todo we don't currently have an agreed data source
     */
    public function getPageHeadingSuccess($stayTypeId)
    {
        $validId = array(1, 2);
        return in_array($stayTypeId, $validId);
    }
}
