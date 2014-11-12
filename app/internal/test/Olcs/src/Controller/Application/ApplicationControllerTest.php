<?php

/**
 * Application Controller Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace OlcsTest\Controller\Application;

use PHPUnit_Framework_TestCase;
use OlcsTest\Bootstrap;
use Common\Service\Entity\ApplicationEntityService;
use Common\Service\Entity\LicenceEntityService;
use Common\Service\Data\CategoryDataService;
use Common\Service\Data\FeeTypeDataService;
use Common\Service\Entity\FeeEntityService;
use CommonTest\Traits\MockDateTrait;
/**
 * Application Controller Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class ApplicationControllerTest extends PHPUnit_Framework_TestCase
{
    use MockDateTrait;

    private $sut;
    private $sm;
    private $mockParams;
    private $mockRouteParams;
    private $pluginManager;

    protected function setUp()
    {
        $this->sm = Bootstrap::getServiceManager();
        $this->sm->setAllowOverride(true);

        $this->sut = $this->getMock('\Olcs\Controller\Application\ApplicationController', array('render'));
        $this->sut->setServiceLocator($this->sm);
        $this->pluginManager = $this->sut->getPluginManager();
    }

    /**
     * @group application_controller
     */
    public function testCaseAction()
    {
        $this->mockRender();

        $view = $this->sut->caseAction();

        $this->assertEquals('application/index', $view->getTemplate());
    }

    /**
     * @group application_controller
     */
    public function testEnvironmentalAction()
    {
        $this->mockRender();

        $view = $this->sut->environmentalAction();

        $this->assertEquals('application/index', $view->getTemplate());
    }

    /**
     * @group application_controller
     */
    public function testDocumentAction()
    {
        $this->mockRender();

        $view = $this->sut->documentAction();

        $this->assertEquals('application/index', $view->getTemplate());
    }

    /**
     * @group application_controller
     */
    public function testProcessingAction()
    {
        $this->mockRender();

        $view = $this->sut->processingAction();

        $this->assertEquals('application/index', $view->getTemplate());
    }

    /**
     * @group application_controller
     */
    public function testGrantActionWithGet()
    {
        $id = 7;

        $this->mockRouteParam('application', $id);

        $this->mockRender();

        $request = $this->sut->getRequest();
        $request->setMethod('GET');

        $formHelper = $this->getMock('\stdClass', ['createForm']);
        $formHelper->expects($this->once())
            ->method('createForm')
            ->with('GenericConfirmation')
            ->will($this->returnValue('FORM'));
        $this->sm->setService('Helper\Form', $formHelper);

        $view = $this->sut->grantAction();
        $this->assertEquals('application/grant', $view->getTemplate());
        $this->assertEquals('FORM', $view->getVariable('form'));
    }

    /**
     * @group application_controller
     */
    public function testGrantActionWithCancelButton()
    {
        $id = 7;
        $post = array(
            'form-actions' => array(
                'cancel' => 'foo'
            )
        );

        $this->mockRouteParam('application', $id);

        $request = $this->sut->getRequest();
        $request->setMethod('POST');
        $request->setPost(new \Zend\Stdlib\Parameters($post));

        $redirect = $this->mockRedirect();
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with('lva-application', array('application' => 7))
            ->will($this->returnValue('REDIRECT'));

        $this->assertEquals('REDIRECT', $this->sut->grantAction());
    }

    /**
     * @group application_controller
     */
    public function testGrantActionWithPost()
    {
        $id = 7;
        $licenceId = 8;
        $userId = 6;
        $teamId = 1;
        $taskId = 9;
        $feeTypeId = 700;
        $fixedValue = '0.00';
        $fiveYearValue = '10.00';
        $appDate = '2012-01-01';
        $date = date('Y-m-d');
        $this->mockDate($date);

        $post = array();

        $this->mockRouteParam('application', $id);

        $request = $this->sut->getRequest();
        $request->setMethod('POST');
        $request->setPost(new \Zend\Stdlib\Parameters($post));

        $mockApplicationService = $this->getMock(
            '\stdClass',
            [
                'getLicenceIdForApplication',
                'forceUpdate',
                'getApplicationDate'
            ]
        );
        $mockApplicationService->expects($this->once())
            ->method('getLicenceIdForApplication')
            ->with($id)
            ->will($this->returnValue($licenceId));
        $mockApplicationService->expects($this->once())
            ->method('forceUpdate')
            ->with(
                $id,
                array(
                    'status' => ApplicationEntityService::APPLICATION_STATUS_GRANTED,
                    'grantedDate' => $date
                )
            );
        $this->sm->setService('Entity\Application', $mockApplicationService);

        $mockLicenceService = $this->getMock('\stdClass', ['forceUpdate', 'getTypeOfLicenceData']);
        $mockLicenceService->expects($this->once())
            ->method('forceUpdate')
            ->with(
                $licenceId,
                array(
                    'status' => LicenceEntityService::LICENCE_STATUS_GRANTED,
                    'grantedDate' => $date
                )
            );
        $this->sm->setService('Entity\Licence', $mockLicenceService);

        $mockUserService = $this->getMock('\stdClass', ['getCurrentUser']);
        $mockUserService->expects($this->once())
            ->method('getCurrentUser')
            ->will($this->returnValue(array('id' => $userId, 'team' => array('id' => $teamId))));
        $this->sm->setService('Entity\User', $mockUserService);

        $expectedTask = array(
            'category' => CategoryDataService::CATEGORY_APPLICATION,
            'taskSubCategory' => CategoryDataService::TASK_SUB_CATEGORY_APPLICATION_GRANT_FEE_DUE,
            'description' => 'Grant fee due',
            'actionDate' => $date,
            'assignedToUser' => $userId,
            'assignedToTeam' => $teamId,
            'isClosed' => 'N',
            'urgent' => 'N',
            'application' => $id,
            'licence' => $licenceId,
        );

        $mockTaskService = $this->getMock('\stdClass', ['save']);
        $mockTaskService->expects($this->once())
            ->method('save')
            ->with($expectedTask)
            ->will($this->returnValue(array('id' => $taskId)));
        $this->sm->setService('Entity\Task', $mockTaskService);

        $mockApplicationService->expects($this->once())
            ->method('getApplicationDate')
            ->will($this->returnValue($appDate));

        $typeOfLicenceData = array(
            'goodsOrPsv' => LicenceEntityService::LICENCE_CATEGORY_GOODS_VEHICLE,
            'licenceType' => LicenceEntityService::LICENCE_TYPE_STANDARD_NATIONAL,
            'niFlag' => 'N'
        );

        $mockLicenceService->expects($this->once())
            ->method('getTypeOfLicenceData')
            ->with($licenceId)
            ->will($this->returnValue($typeOfLicenceData));

        $feeType = array(
            'id' => $feeTypeId,
            'fixedValue' => $fixedValue,
            'fiveYearValue' => $fiveYearValue,
            'description' => 'fee'
        );

        $mockFeeType = $this->getMock('\stdClass', ['getLatest']);
        $mockFeeType->expects($this->once())
            ->method('getLatest')
            ->with(
                FeeTypeDataService::FEE_TYPE_GRANT,
                LicenceEntityService::LICENCE_CATEGORY_GOODS_VEHICLE,
                LicenceEntityService::LICENCE_TYPE_STANDARD_NATIONAL,
                $appDate,
                false
            )
            ->will($this->returnValue($feeType));
        $this->sm->setService('Data\FeeType', $mockFeeType);

        $feeData = array(
            'amount' => $fiveYearValue,
            'application' => $id,
            'licence' => $licenceId,
            'invoicedDate' => $date,
            'feeType' => $feeTypeId,
            'description' => 'fee for application ' . $id,
            'feeStatus' => FeeEntityService::STATUS_OUTSTANDING,
            'task' => $taskId
        );

        $mockFeeService = $this->getMock('\stdClass', ['save']);
        $mockFeeService->expects($this->once())
            ->method('save')
            ->with($feeData);
        $this->sm->setService('Entity\Fee', $mockFeeService);

        $mockFlashMessenger = $this->getMock('\stdClass', ['addSuccessMessage']);
        $mockFlashMessenger->expects($this->once())
            ->method('addSuccessMessage')
            ->with('The application was granted successfully');
        $this->sm->setService('Helper\FlashMessenger', $mockFlashMessenger);

        $redirect = $this->mockRedirect();
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with('lva-application', array('application' => 7))
            ->will($this->returnValue('REDIRECT'));

        $this->assertEquals('REDIRECT', $this->sut->grantAction());
    }

    /**
     * @group application_controller
     */
    public function testUndoGrantActionWithGet()
    {
        $id = 7;

        $this->mockRouteParam('application', $id);

        $this->mockRender();

        $request = $this->sut->getRequest();
        $request->setMethod('GET');

        $formHelper = $this->getMock('\stdClass', ['createForm']);
        $formHelper->expects($this->once())
            ->method('createForm')
            ->with('GenericConfirmation')
            ->will($this->returnValue('FORM'));
        $this->sm->setService('Helper\Form', $formHelper);

        $view = $this->sut->undoGrantAction();
        $this->assertEquals('application/undo-grant', $view->getTemplate());
        $this->assertEquals('FORM', $view->getVariable('form'));
    }

    /**
     * @group application_controller
     */
    public function testUndoGrantActionWithCancelButton()
    {
        $id = 7;
        $post = array(
            'form-actions' => array(
                'cancel' => 'foo'
            )
        );

        $this->mockRouteParam('application', $id);

        $request = $this->sut->getRequest();
        $request->setMethod('POST');
        $request->setPost(new \Zend\Stdlib\Parameters($post));

        $redirect = $this->mockRedirect();
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with('lva-application', array('application' => 7))
            ->will($this->returnValue('REDIRECT'));

        $this->assertEquals('REDIRECT', $this->sut->undoGrantAction());
    }

    /**
     * @group application_controller
     */
    public function testUndoGrantActionWithPost()
    {
        $id = 7;
        $licenceId = 8;
        $post = array();

        $this->mockRouteParam('application', $id);

        $request = $this->sut->getRequest();
        $request->setMethod('POST');
        $request->setPost(new \Zend\Stdlib\Parameters($post));

        $mockApplicationService = $this->getMock(
            '\stdClass',
            [
                'getLicenceIdForApplication',
                'forceUpdate',
                'getApplicationDate'
            ]
        );
        $mockApplicationService->expects($this->once())
            ->method('getLicenceIdForApplication')
            ->with($id)
            ->will($this->returnValue($licenceId));
        $mockApplicationService->expects($this->once())
            ->method('forceUpdate')
            ->with(
                $id,
                array(
                    'status' => ApplicationEntityService::APPLICATION_STATUS_UNDER_CONSIDERATION,
                    'grantedDate' => null
                )
            );
        $this->sm->setService('Entity\Application', $mockApplicationService);

        $mockLicenceService = $this->getMock('\stdClass', ['forceUpdate', 'getTypeOfLicenceData']);
        $mockLicenceService->expects($this->once())
            ->method('forceUpdate')
            ->with(
                $licenceId,
                array(
                    'status' => LicenceEntityService::LICENCE_STATUS_UNDER_CONSIDERATION,
                    'grantedDate' => null
                )
            );
        $this->sm->setService('Entity\Licence', $mockLicenceService);

        $mockFeeService = $this->getMock('\stdClass', ['cancelForLicence']);
        $mockFeeService->expects($this->once())
            ->method('cancelForLicence')
            ->with($licenceId);
        $this->sm->setService('Entity\Fee', $mockFeeService);

        $taskQuery = array(
            'category' => CategoryDataService::CATEGORY_APPLICATION,
            'taskSubCategory' => CategoryDataService::TASK_SUB_CATEGORY_APPLICATION_GRANT_FEE_DUE,
            'licence' => $licenceId,
            'application' => $id
        );

        $mockTaskService = $this->getMock('\stdClass', ['closeByQuery']);
        $mockTaskService->expects($this->once())
            ->method('closeByQuery')
            ->with($taskQuery);
        $this->sm->setService('Entity\Task', $mockTaskService);

        $mockFlashMessenger = $this->getMock('\stdClass', ['addSuccessMessage']);
        $mockFlashMessenger->expects($this->once())
            ->method('addSuccessMessage')
            ->with('The application grant has been undone successfully');
        $this->sm->setService('Helper\FlashMessenger', $mockFlashMessenger);

        $redirect = $this->mockRedirect();
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with('lva-application', array('application' => 7))
            ->will($this->returnValue('REDIRECT'));

        $this->assertEquals('REDIRECT', $this->sut->undoGrantAction());
    }

    /**
     * Helper method
     * @todo when these helper methods are required in more than 1 place, we need to abstract them away
     */
    protected function mockRouteParam($name, $value)
    {
        $this->mockRouteParams[$name] = $value;

        if ($this->mockParams === null) {
            $this->mockParams = $this->getMock('\Zend\Mvc\Controller\Plugin\Params', array('__invoke'));

            $this->mockParams->expects($this->any())
                ->method('__invoke')
                ->will($this->returnCallback(array($this, 'getRouteParam')));

            $this->pluginManager->setService('params', $this->mockParams);
        }
    }

    /**
     * Helper method
     */
    public function getRouteParam($name)
    {
        return isset($this->mockRouteParams[$name]) ? $this->mockRouteParams[$name] : null;
    }

    /**
     * Helper method
     */
    protected function mockRender()
    {
        $this->sut->expects($this->once())
            ->method('render')
            ->will(
                $this->returnCallback(
                    function ($view) {
                        return $view;
                    }
                )
            );
    }

    /**
     * Helper method
     */
    protected function mockRedirect()
    {
        $mockRedirect = $this->getMock('\Zend\Mvc\Controller\Plugin\Redirect', array('toRoute'));
        $this->pluginManager->setService('Redirect', $mockRedirect);
        return $mockRedirect;
    }
}
