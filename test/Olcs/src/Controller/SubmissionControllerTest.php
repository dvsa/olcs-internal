<?php

/**
 * Search controller form post tests
 *
 * @author adminmwc
 */

namespace OlcsTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class SubmissionControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__.'/../../../../'  . 'config/application.config.php'
        );
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'getServiceLocator',
                'setBreadcrumb',
                'generateFormWithData',
                'generateForm',
                'redirect',
                'params',
                'getParams',
                'makeRestCall',
                'setData',
                'processEdit',
                'processAdd',
                'getViewModel',
                'createSubmission',
                'getSubmissionView',
                'getRequest',
                'url',
                'setSubmissionBreadcrumb'
            )
        );
        $this->controller->routeParams = array();
        $this->licenceData = array(
            'id' => 7,
            'licenceType' => 'Standard National',
            'goodsOrPsv' => 'Psv'
        );
        
        parent::setUp();
    }
    
    public function testAddPostAction()
    {
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'add');
        
        $this->controller->expects($this->once())
            ->method('setSubmissionBreadcrumb')
            ->with();
        
        $this->controller->expects($this->once())
            ->method('createSubmission')
            ->with($this->controller->routeParams)
            ->will($this->returnValue('{"submission":{}}'));
        
        $getRequest = $this->getMock('\stdClass', array('isPost'));
        
        $getRequest->expects($this->once())
            ->method('isPost')
            ->will($this->returnValue(true));
        
        $this->controller->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($getRequest));
        
        $data = array(
            'createdBy' => 1,
            'text' => '{"submission":{}}',
            'vosaCase' => 54);
        
        $this->controller->expects($this->once())
            ->method('processAdd')
            ->with($data, 'Submission')
            ->will($this->returnValue(8));
        
        $redirect = $this->getMock('\stdClass', array('toRoute'));
        
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with('submission', array('licence' => 7, 'case' => 54, 'id' => null, 'action' => 'add'));
        
        $this->controller->expects($this->once())
             ->method('redirect')
             ->will($this->returnValue($redirect));
        
        $this->controller->addAction();
    }
    
    public function testAddGetAction()
    {
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'add');
        
        $this->controller->expects($this->once())
            ->method('setSubmissionBreadcrumb')
            ->with();
        
        $this->controller->expects($this->once())
            ->method('createSubmission')
            ->with($this->controller->routeParams)
            ->will($this->returnValue('{"submission":{}}'));
        
        $getRequest = $this->getMock('\stdClass', array('isPost'));
        
        $getRequest->expects($this->once())
            ->method('isPost')
            ->will($this->returnValue(false));
        
        $this->controller->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($getRequest));
        
        $this->controller->expects($this->once())
            ->method('getSubmissionView')
            ->with(array('data' => array('submission' => array())))
            ->will($this->returnValue('view}'));
        
        $this->controller->addAction();
    }
    
    public function testEditPostAction()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'getEditSubmissionData',
                'getSubmissionView',
                'getRequest',
                'setSubmissionBreadcrumb'
            )
        );
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'add');
        
        $this->controller->expects($this->once())
            ->method('setSubmissionBreadcrumb')
            ->with();
        
        $getRequest = $this->getMock('\stdClass', array('isPost'));
        
        $getRequest->expects($this->once())
            ->method('isPost')
            ->will($this->returnValue(false));
        
        $this->controller->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($getRequest));
        
        $this->controller->expects($this->once())
            ->method('getEditSubmissionData')
            ->will($this->returnValue('{"submission":{}}'));
        
        $this->controller->expects($this->once())
            ->method('getSubmissionView')
            ->with('{"submission":{}}');
        
        $this->controller->editAction();
    }
    
    public function testEditRedirectAction()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'getEditSubmissionData',
                'getSubmissionView',
                'getRequest',
                'redirect',
                'params',
                'setSubmissionBreadcrumb'
            )
        );
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'add');
        
        $this->controller->expects($this->once())
            ->method('setSubmissionBreadcrumb')
            ->with();
        
        $getRequest = $this->getMock('\stdClass', array('isPost'));
        
        $getRequest->expects($this->once())
            ->method('isPost')
            ->will($this->returnValue(true));
        
        $this->controller->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($getRequest));
        
        $redirect = $this->getMock('\stdClass', array('toRoute'));
        
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with('submission', array('licence' => 7, 'case' => 54, 'id' => null, 'action' => 'edit'));
        
        $this->controller->expects($this->once())
             ->method('redirect')
             ->will($this->returnValue($redirect));
        
         $params = $this->getMock('\stdClass', array('fromPost'));
         
         $params->expects($this->once())
            ->method('fromPost')
            ->with('id')
            ->will($this->returnValue(null));
         
         $this->controller->expects($this->once())
             ->method('params')
             ->will($this->returnValue($params));
        
        $this->controller->editAction();
    }
    
    public function testgetEditSubmissionData()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'makeRestCall',
                'getServiceLocator',
            )
        );
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'add', 'id' => 8);
        $bundle = array(
            'children' => array(
                'submissionActions' => array(
                    'properties' => 'ALL',
                    'children' => array(
                        'userSender' => array(
                            'properties' => 'ALL'
                        ),
                        'userRecipient' => array(
                            'properties' => 'ALL'
                        ),
                    )
                )
            )
        );
        
        $this->controller->expects($this->once())
            ->method('makeRestCall')
            ->with('Submission', 'GET', array('id' => $this->controller->routeParams['id']), $bundle)
            ->will(
                $this->returnValue(array(
                'text' => '{"submission":{}}',
                'submissionActions' => array(
                    array(
                        'submissionActionType' => 'decision',
                        'submissionActionStatus' => 'submission_decision.disagree'
                    )
                )))
            );
        
        $serviceLocator = $this->getMock('\stdClass', array('get'));
        
        $serviceLocator->expects($this->once())
            ->method('get')
            ->with('config')
            ->will(
                $this->returnValue(array(
                'static-list-data' => array('submission_decision' =>
                    array(
                        'submission_decision.disagree' => 'Disagree'
                    ))
                ))
            );
        
        $this->controller->expects($this->once())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceLocator));
        
        $this->controller->getEditSubmissionData();
    }
    
    public function testgetSubmissionView()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'getViewModel',
                'url',
            )
        );
        
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'post', 'id' => 8);
        
        $url = $this->getMock('\stdClass', array('fromRoute'));
        
        $url->expects($this->once())
            ->method('fromRoute')
            ->with('submission', $this->controller->routeParams)
            ->will($this->returnValue('/licence/7/case/28/submission/edit/166'));
        
        $this->controller->expects($this->once())
            ->method('url')
            ->will($this->returnValue($url));
        
        $viewModel = $this->getMock('\stdClass', array('setTemplate'));
        
       $viewModel->expects($this->once())
            ->method('setTemplate')
            ->with('submission/page');
        
        $this->controller->expects($this->once())
            ->method('getViewModel')
            ->with(
                array(
                    'params' => array(
                        'formAction' => '/licence/7/case/28/submission/edit/166',
                        'pageTitle' => 'case-submission',
                        'pageSubTitle' => 'case-submission-text',
                        'submission' => array()
                    )
                )
            )
            ->will($this->returnValue($viewModel));
         
        $this->controller->getSubmissionView(array());
    }
    
    public function testPostDecisionAction()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'getViewModel',
                'params',
                'redirect',
                'backToCaseButton'
            )
        );
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'decision', 'id' => 8);
        
        $params = $this->getMock('\stdClass', array('fromPost'));
         
         $params->expects($this->at(0))
            ->method('fromPost')
            ->with('decision')
            ->will($this->returnValue(true));
         
         $this->controller->expects($this->atLeastOnce())
             ->method('params')
             ->will($this->returnValue($params));
         
         $redirect = $this->getMock('\stdClass', array('toRoute'));
        
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with('submission', $this->controller->routeParams);
        
        $this->controller->expects($this->once())
             ->method('redirect')
             ->will($this->returnValue($redirect));
         
         $this->controller->postAction();
    }
    
    public function testPostRecommendAction()
    {
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'recommendation', 'id' => 8);
        $params = $this->getMock('\stdClass', array('fromPost'));
         
         $params->expects($this->at(0))
            ->method('fromPost')
            ->with('decision')
            ->will($this->returnValue(false));
         
         $params->expects($this->at(1))
            ->method('fromPost')
            ->with('recommend')
            ->will($this->returnValue(true));
         
         $this->controller->expects($this->atLeastOnce())
             ->method('params')
             ->will($this->returnValue($params));
         
         $redirect = $this->getMock('\stdClass', array('toRoute'));
        
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with('submission', $this->controller->routeParams);
        
        $this->controller->expects($this->once())
             ->method('redirect')
             ->will($this->returnValue($redirect));
         
         $this->controller->postAction();
    }
    
    public function testRecommendationAction()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'backToCaseButton',
                'formView',
                'setSubmissionBreadcrumb',
                
            )
        );
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'recommendation', 'id' => 8);
        /*$this->controller->expects($this->once())
             ->method('backToCaseButton');*/
        
        $this->controller->expects($this->once())
            ->method('setSubmissionBreadcrumb')
            ->with();
        
        $this->controller->expects($this->once())
            ->method('formView')
            ->with('recommend');
        
        $this->controller->recommendationAction();
    }
    
    public function testDecisionAction()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'backToCaseButton',
                'formView',
                'setSubmissionBreadcrumb',
                
            )
        );
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'recommendation', 'id' => 8);
        /*$this->controller->expects($this->once())
             ->method('backToCaseButton');*/
        
        $this->controller->expects($this->once())
            ->method('setSubmissionBreadcrumb')
            ->with();
        
        $this->controller->expects($this->once())
            ->method('formView')
            ->with('decision');
        
        $this->controller->decisionAction();
    }
    
    public function testCreateSubmission()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'makeRestCall',
                'getServiceLocator'
            )
        );
        $this->licenceData['licenceType'] = 'blah';
        $this->controller->expects($this->once())
            ->method('makeRestCall')
            ->with('Licence', 'GET', array('id' => 7))
            ->will($this->returnValue($this->licenceData));
        
        $serviceLocator = $this->getMock('\stdClass', array('get'));
        
        $serviceLocator->expects($this->once())
            ->method('get')
            ->with('config')
            ->will(
                $this->returnValue(
                    array(
                        'submission_config' => array(
                            'sections' => array(
                                'case-summary-info' => null,
                                'transport-managers' => array(
                                    'exclude' => array(
                                        'column' => 'licenceType',
                                        'values' => array(
                                            'standard national',
                                            'standard international'
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            );
        
        $this->controller->expects($this->once())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceLocator));
        
        $this->controller->createSubmission(array('licence' => 7, 'case' => 54));
    }
    
    public function testFormView()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'makeRestCall',
                'getServiceLocator',
                'getFormWithUsers',
                'formPost',
                'getViewModel'
            )
        );
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'decision', 'id' => 8);
        $this->controller->expects($this->once())
            ->method('getFormWithUsers')
            ->with('decision', array('submission' => 8, 'userSender' => 1))
            ->will($this->returnValue('form'));
        
        $this->controller->expects($this->once())
            ->method('formPost')
            ->with('form', 'processRecDecForm')
            ->will($this->returnValue('form'));
        
        $viewModel = $this->getMock('\stdClass', array('setTemplate'));
        
       $viewModel->expects($this->once())
            ->method('setTemplate')
            ->with('form');
        
        $this->controller->expects($this->once())
            ->method('getViewModel')
            ->with(
                array(
                'form' => 'form',
                'params' => array(
                    'pageTitle' => "submission-decision",
                    'pageSubTitle' => "submission-decision-text",
                )
            )
            )
            ->will($this->returnValue($viewModel));
        
        $this->controller->formView('decision');
    }
    
    public function testProcessRecDecForm()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'processAdd',
                'redirect'
            )
        );
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'decision', 'id' => 8);
         $this->controller->expects($this->once())
            ->method('processAdd')
            ->with(array('main' => array()))
            ->will($this->returnValue(array('id' => 8)));
         
         $redirect = $this->getMock('\stdClass', array('toRoute'));
        
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with('case_manage', array('licence' => 7, 'case' => 54, 'tab' => 'overview'));
        
        $this->controller->expects($this->once())
             ->method('redirect')
             ->will($this->returnValue($redirect));
         
        $this->controller->processRecDecForm(array('main' => array()));
    }
    
    public function testBackToCaseButton()
    {
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'decision', 'id' => 8);
        $redirect = $this->getMock('\stdClass', array('toRoute'));
        
        $redirect->expects($this->once())
            ->method('toRoute')
            ->with('submission', array('licence' => 7, 'case' => 54, 'id' => 8, 'action' => 'edit'));
        
        $this->controller->expects($this->once())
             ->method('redirect')
             ->will($this->returnValue($redirect));
         
        $this->controller->backToCaseButton(array('main' => array()));
    }
    
    public function testGetUserList()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'makeRestCall'
            )
        );
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'decision', 'id' => 8);
        $users = array(
            'Results' => array(
                array(
                    'id' => 1,
                    'displayName' => 'Fred Smith'
                )
            )
        );
        
        $this->controller->expects($this->once())
            ->method('makeRestCall')
            ->with('User', 'GET', array())
            ->will($this->returnValue($users));
        
        $this->controller->getUserList();
    }
    
    public function testGetFormWithUsers()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'getUserList',
                'getFormGenerator',
            )
        );
        
        $this->controller->expects($this->once())
            ->method('getUserList')
            ->will($this->returnValue('user-list'));
        
        $formGenerator = $this->getMock('\stdClass', array('getFormConfig', 'setFormConfig'));
        
        $formConfig = [
            'decision' => [
                'name' => 'decision',
                'attributes' => [
                    'method' => 'post',
                ],
                'fieldsets' => ['main' =>
                    [
                        'name' => 'main',
                        'options' => [
                            'label' => 'Add decision'
                        ],
                        'elements' => [
                            'userRecipient' => [
                                'label' => 'Send to',
                                'type' => 'select',
                                'value_options' => 'user-list',
                                'required' => true
                            ],
                        ]
                    ]
                ],
            ]
        ];
        
        $formGenerator->expects($this->once())
            ->method('getFormConfig')
            ->will($this->returnValue($formConfig));
        
        $this->controller->expects($this->once())
            ->method('getFormGenerator')
            ->will($this->returnValue($formGenerator));
        
        $createForm = $this->getMock('\stdClass', array('createForm'));
        $form = $this->getMock('\stdClass', array('setData'));
        
        $createForm->expects($this->once())
            ->method('createForm')
            ->with('decision')
            ->will($this->returnValue($form));
        
        $formGenerator->expects($this->once())
            ->method('setFormConfig')
            ->with($formConfig)
            ->will($this->returnValue($createForm));
        
        $form->expects($this->once())
            ->method('setData')
            ->with(array());
        
        $this->controller->getFormWithUsers('decision', array());
    }
    
    public function testSetSubmissionBreadcrumb()
    {
        $this->controller = $this->getMock(
            '\Olcs\Controller\SubmissionController',
            array(
                'makeRestCall',
                'setBreadcrumb'
            )
        );
        $this->controller->routeParams = array('case' => 54, 'licence' => 7, 'action' => 'decision', 'id' => 8);
        $thisNavRoutes = array(
                'licence_case_list/pagination' => array('licence' => 7),
                'case_manage' => array(
                    'case' => 54,
                    'licence' => 7,
                    'tab' => 'overview'
                )
        );
        $this->controller->expects($this->once())
            ->method('setBreadcrumb')
            ->with($thisNavRoutes);
        $this->controller->setSubmissionBreadcrumb(array());
    }
}
