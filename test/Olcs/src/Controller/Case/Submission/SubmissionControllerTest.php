<?php
namespace OlcsTest\Controller\Submission;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OlcsTest\Bootstrap;
use Mockery as m;
use Zend\Http\Request;
use Zend\Http\Response;
use Olcs\TestHelpers\ControllerRouteMatchHelper;

/**
 * Submission controller form post tests
 *
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
class SubmissionControllerTest extends AbstractHttpControllerTestCase
{

    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../../../' . 'config/application.config.php'
        );
        $this->controller = $this->getMock(
            '\Olcs\Controller\Cases\Submission\SubmissionController', array(
                'getParams',
                'getFromPost',
                'getPersist',
                'setPersist',
                'getCase',
                'getForm',
                'generateFormWithData',
                'getDataForForm',
                'callParentProcessSave',
                'callParentSave',
                'callParentProcessLoad',
                'createSubmissionSection',
                'getServiceLocator'
            )
        );
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller->setServiceLocator($serviceManager);
        $this->routeMatchHelper = new ControllerRouteMatchHelper();
        $this->controller->routeParams = array();

        parent::setUp();
    }

    /**
     * Test process save of new submissions
     *
     * @param $dataToSave
     * @param $expectedResult
     *
     * @dataProvider getSubmissionSectionsToProcessSaveProvider
     */
    public function testProcessSaveAddNew($dataToSave, $expectedResult)
    {
        $this->controller->expects($this->once())
            ->method('callParentProcessSave')
            ->with($dataToSave)
            ->will($this->returnValue($expectedResult));

        $mockResponse = m::mock('\Zend\Http\Response');

        $mockRedirectPlugin = m::mock('\Zend\Controller\Plugin\Redirect');
        $mockRedirectPlugin->shouldReceive('toRoute')->with(
            'submission',
            ['action' => 'details', 'submission' => $expectedResult['id']],
            [],
            true
        )->andReturn($mockResponse);

        $mockControllerPluginManager = m::mock('\Zend\Mvc\Controller\PluginManager');
        $mockControllerPluginManager->shouldReceive('setController')->withAnyArgs();
        $mockControllerPluginManager->shouldReceive('get')->with('redirect', '')->andReturn($mockRedirectPlugin);

        $this->controller->setPluginManager($mockControllerPluginManager);

        $this->controller->processSave($dataToSave);
    }

    /**
     * Test processLoad of submissions
     *
     * @param $dataToLoad
     * @param $loadedData
     *
     * @dataProvider getSubmissionSectionsToLoadProvider
     */
    public function testProcessLoad($dataToLoad, $loadedData)
    {
        $this->controller->expects($this->once())
            ->method('callParentProcessLoad')
            ->with($dataToLoad)
            ->will($this->returnValue($dataToLoad));

        $this->controller->expects($this->once())
            ->method('getCase')
            ->will($this->returnValue(['id' => 24]));

        $result = $this->controller->processLoad($dataToLoad);

        $this->assertEquals($result, $loadedData);

    }

    /**
     * Tests the first time a user goes to the submission form
     */
    public function testAlterFormBeforeValidationNoSubmissionType()
    {
        $mockForm = $this->getMock(
            '\Zend\Form\Form',
            array(
                'remove',
                'get'
            )
        );

        $mockForm->expects($this->once())
            ->method('remove')
            ->with($this->equalTo('form-actions'));

        $this->controller->alterFormBeforeValidation($mockForm);
    }

    /**
     * Tests the submission type being chosen
     */
    public function testAlterFormBeforeValidationSubmissionTypePosted()
    {
        $mockPostData = [
            'submissionSections' => [
                'submissionTypeSubmit' => 'some_type'
            ]
        ];
        $this->controller->expects($this->once())
            ->method('getFromPost')
            ->with('fields')
            ->willReturn($mockPostData);

        $mockForm = $this->getMock(
            '\Zend\Form\Form'
        );

        $this->controller->expects($this->once())
            ->method('setPersist')
            ->with($this->equalTo(false));

        $this->controller->alterFormBeforeValidation($mockForm);
    }

    public function testSave()
    {

        $data = ['submissionSections' =>
            [
                'submissionType' => 'bar',
                'sections' => [
                    0 => 'section1',
                    1 => 'section2'
                ]
            ]
        ];
        $service = 'Submission';

        $mockConfig = ['submission_config' =>
            [
                'sections' =>
                    [
                        'section1' => 'foo'
                    ]
            ]
        ];

        $mockSubmissionService = m::mock('Olcs\Service\Data\Submission');
        $mockRestHelper = m::mock('RestHelper');

        $mockRestHelper->shouldReceive('makeRestCall')->withAnyArgs()->andReturn(['id' => 99]);

        $mockSubmissionService->shouldReceive('createSubmissionSection')
            ->withAnyArgs()
            ->andReturn(['sectionData']);

        $mockServiceManager = m::mock('\Zend\ServiceManager\ServiceManager');

        $mockServiceManager->shouldReceive('get')->with('HelperService')->andReturnSelf();
        $mockServiceManager->shouldReceive('getHelperService')->with('RestHelper')->andReturn($mockRestHelper);

        $mockServiceManager->shouldReceive('get->getHelperService')->with('RestService')->andReturn($mockRestHelper);

        $mockServiceManager->shouldReceive('get')->with('config')->andReturn($mockConfig);
        $mockServiceManager->shouldReceive('get')->with('Olcs\Service\Data\Submission')
            ->andReturn($mockSubmissionService);

        $sut = new \Olcs\Controller\Cases\Submission\SubmissionController();
        $event = $this->routeMatchHelper->getMockRouteMatch(array('controller' => 'submission'));
        $sut->setEvent($event);

        $sut->getEvent()->getRouteMatch()->setParam('case', 24);

        $sut->setServiceLocator($mockServiceManager);

        $result = $sut->save($data, $service);

        $this->assertEquals(['id' => 99], $result);
    }

    public function testDetailsAction()
    {
        $sut = new \Olcs\Controller\Cases\Submission\SubmissionController();

        $submissionId = 99;
        $mockSubmission = ['submissionType' =>
            [
                'id' => 'foo'
            ]
        ];

        $mockSelectedSectionArray = [
            0 => [
                'sectionId' => 'section1',
                'data' => []
            ]
        ];

        $mockConfig = ['submission_config' =>
            [
                'sections' =>
                    [
                        'section1' => 'foo'
                    ]
            ]
        ];

        $mockSubmissionTitle = 'Section title';
        $placeholder = new \Zend\View\Helper\Placeholder();

        $mockViewHelperManager = new \Zend\View\HelperPluginManager();
        $mockViewHelperManager->setService('placeholder', $placeholder);

        $event = $this->routeMatchHelper->getMockRouteMatch(array('controller' => 'submission_section_comment'));
        $sut->setEvent($event);

        $sut->getEvent()->getRouteMatch()->setParam('submission', $submissionId);

        $mockSubmissionService = m::mock('Olcs\Service\Data\Submission');
        $mockSubmissionService->shouldReceive('fetchSubmissionData')
            ->with($submissionId)
            ->andReturn($mockSubmission);

        $mockSubmissionService->shouldReceive('getSubmissionTypeTitle')
            ->with($mockSubmission['submissionType']['id'])
            ->andReturn($mockSubmissionTitle);

        $mockSubmissionService->shouldReceive('extractSelectedSubmissionSectionsData')
            ->with(array_merge($mockSubmission, ['submissionTypeTitle' => $mockSubmissionTitle]))
            ->andReturn($mockSelectedSectionArray);

        $mockSubmissionService->shouldReceive('getAllSectionsRefData')
            ->andReturn($mockSelectedSectionArray);

        $mockServiceManager = m::mock('\Zend\ServiceManager\ServiceManager');

        $mockServiceManager->shouldReceive('get')->with('config')->andReturn($mockConfig);

        $mockServiceManager->shouldReceive('get')->with('Olcs\Service\Data\Submission')
            ->andReturn($mockSubmissionService);

        $mockServiceManager->shouldReceive('get')->with('viewHelperManager')
            ->andReturn($mockViewHelperManager);

        $sut->setServiceLocator($mockServiceManager);

        $sut->detailsAction();

        $this->assertEquals(
            $mockSelectedSectionArray,
            $mockViewHelperManager->get('placeholder')->getContainer('selectedSectionsArray')->getValue()
        );
        $this->assertEquals(
            array_merge($mockSubmission, ['submissionTypeTitle' => $mockSubmissionTitle]),
            $mockViewHelperManager->get('placeholder')->getContainer('submission')->getValue()
        );
    }

    public function getSubmissionTitlesProvider()
    {
        return array(
            array(
                array(
                    'submissionTypeId' => 'submission_type_o_test',
                    'submissionTitles' => array(
                        array(
                            'id' => 'submission_type_t_test',
                            'description' => 'test title'
                        )
                    )
                ),
                'test title'
            ),
            array(
                array(
                    'submissionTypeId' => 'submission_type_o_testdoesntexist',
                    'submissionTitles' => array(
                        array(
                            'id' => 'submission_type_t_test',
                            'description' => 'test title'
                        )
                    )
                ),
                ''
            )
        );

    }

    public function getSubmissionSectionsToProcessSaveProvider()
    {
        return array(
            array(
                array(
                    'fields' =>
                        array(
                            'submissionSections[submissionType]' => 'sub type 1',
                            'submissionSections[sections]' => ['section1', 'section2']
                        )
                ),
                array(
                    'id' => 1
                )
            )
        );

    }

    public function getSubmissionSectionsToLoadProvider()
    {
        return array(
            array(
                array(
                    'id' => 1,
                    'version' => 1,
                    'submissionType' => 'foo',
                    'dataSnapshot' => '[{"sectionId":"submission_section_casu","data":{"data":[]}}]'
                ),
                array(
                    'id' => 1,
                    'version' => 1,
                    'submissionType' => 'foo',
                    'fields' => [
                        'case' => 24,
                        'submissionSections' => [
                            'submissionType' => 'foo',
                            'sections' => ['submission_section_casu']
                        ],
                        'id' => 1,
                        'version' => 1,
                    ],
                    'dataSnapshot' => '[{"sectionId":"submission_section_casu","data":{"data":[]}}]',
                    'case' => 24
                ),
            ),
            array(
                array(
                    'id' => 1,
                    'version' => 1,
                    'submissionSections' => [
                        'sections' => '[{"sectionId":"submission_section_casu"}]',
                    ],
                ),
                array(
                    'id' => 1,
                    'version' => 1,
                    'fields' => [
                        'case' => 24,
                        'submissionSections' => [
                            'sections' => ['submission_section_casu']
                        ],
                    ],
                    'submissionSections' => [
                        'sections' => '[{"sectionId":"submission_section_casu"}]'
                    ],
                )
            )
        );
    }
}
