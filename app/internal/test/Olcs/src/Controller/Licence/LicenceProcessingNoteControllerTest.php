<?php
/**
 * Licence Processing Note controller tests
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace OlcsTest\Controller\Licence;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Mockery as m;

/**
 * Licence Processing Note controller tests
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class LicenceProcessingNoteControllerTest extends AbstractHttpControllerTestCase
{
    protected $testClass = '\Olcs\Controller\Licence\Processing\LicenceProcessingNoteController';

    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__.'/../../../../../config/application.config.php'
        );

        $this->controller = $this->getMock(
            '\Olcs\Controller\Licence\Processing\LicenceProcessingNoteController',
            array(
                'makeRestCall',
                'getLoggedInUser',
                'getTable',
                'generateFormWithData',
                'getFromRoute',
                'getFromPost',
                'getForm',
                'getView',
                'url',
                'renderView',
                'redirectToRoute',
                'processAdd',
                'processEdit',
                'setTableFilters',
                'loadScripts',
                'getRequest'
            )
        );

        $this->view = $this->getMock(
            '\Zend\View\Model\ViewModel',
            array(
                'setTemplate',
            )
        );

        $this->form = $this->getMock(
            '\Zend\Form\Form',
            array(
                'remove',
                'setData'
            )
        );

        parent::setUp();
    }

    public function testIndexAction()
    {
        $licenceId = 7;

        $requestArray = array(
            'page' => 1,
            'sort' => 'priority',
            'order' => 'DESC',
            'limit' => 10,
            'noteType' => ''
        );

        $table = $this->getMock(
            'Common\Service\Table\TableBuilder', [], [], '', false
        );

        $mockParams = m::mock('\Zend\Stdlib\Parameters');
        $mockParams->shouldReceive('toArray')->andReturn($requestArray);

        $mockRequest = m::mock('\Zend\Http\PhpEnvironment\Request');
        $mockRequest->shouldReceive('getQuery')->andReturn($mockParams);

        $this->controller->expects($this->exactly(2))
            ->method('getRequest')
            ->will($this->returnValue($mockRequest));

        $this->getFromRoute(0, 'licence', $licenceId);
        $this->getFromPost(1, 'action', null);
        $this->getFromPost(2, 'id', null);

        $this->controller->expects($this->once())
            ->method('makeRestCall')
            ->will($this->returnValue($this->getSampleResult()));

        $this->controller->expects($this->once())
            ->method('getForm')
            ->with('note-filter')
            ->will($this->returnValue($this->form));

        $this->form->expects($this->once())
            ->method('remove')
            ->with('csrf');

        $this->form->expects($this->once())
            ->method('setData');

        $this->controller->expects($this->once())
            ->method('setTableFilters')
            ->with($this->form);

        $this->controller->expects($this->once())
            ->method('getTable')
            ->will($this->returnValue($table));

        $this->controller->expects($this->once())
            ->method('loadScripts')
            ->with(['note-filter']);

        $this->controller->expects($this->once())
            ->method('getView')
            ->with($this->equalTo(['table' => $table]))
            ->will($this->returnValue($this->view));

        $this->view->expects($this->once())
            ->method('setTemplate')
            ->with($this->controller->getTemplatePrefix() . '/notes/index');

        $this->controller->expects($this->once())
            ->method('renderView');

        $this->controller->indexAction();
    }

    /**
     * Tests for a crud add redirect from index action
     */
    public function testIndexActionAddRedirect()
    {
        $licenceId = 7;
        $action = 'Add';
        $id = null;
        $route = $this->controller->getRoutePrefix() . '/add-note';

        $this->getFromRoute(0, 'licence', $licenceId);
        $this->getFromPost(1, 'action', $action);
        $this->getFromPost(2, 'id', $id);

        $this->controller->expects($this->once())
            ->method('redirectToRoute')
            ->with(
                $this->equalTo($route),
                $this->equalTo(
                    [
                        'action' => strtolower($action),
                        'licence' => $licenceId,
                        'noteType' => 'note_t_lic',
                        'linkedId' => $licenceId,
                        'case' => null

                    ]
                ),
                $this->equalTo([]),
                $this->equalTo(true)
            );

        $this->controller->indexAction();
    }

    /**
     * Tests for a crud edit/delete redirect from index action
     *
     * @dataProvider indexActionModifyRedirectProvider
     *
     * @param string $action
     */
    public function testIndexActionModifyRedirect($action)
    {
        $licenceId = 7;
        $id = 1;
        $route = $this->controller->getRoutePrefix() . '/modify-note';

        $this->getFromRoute(0, 'licence', $licenceId);
        $this->getFromPost(1, 'action', $action);
        $this->getFromPost(2, 'id', $id);

        $this->controller->expects($this->once())
            ->method('redirectToRoute')
            ->with(
                $this->equalTo($route),
                $this->equalTo(['action' => strtolower($action), 'id' => $id]),
                $this->equalTo([]),
                $this->equalTo(true)
            );

        $this->controller->indexAction();
    }

    public function indexActionModifyRedirectProvider()
    {
        return [
            ['Edit', 'Delete']
        ];
    }

    public function testAddAction()
    {
        $licenceId = 7;
        $noteType = 'note_t_lic';
        $linkedId = 1;
        $caseId = 28;

        $this->getFromRoute(0, 'licence', $licenceId);
        $this->getFromRoute(1, 'case', $caseId);
        $this->getFromRoute(2, 'noteType', $noteType);
        $this->getFromRoute(3, 'linkedId', $linkedId);

        $this->controller->expects($this->once())
            ->method('generateFormWithData');

        $this->controller->expects($this->once())
            ->method('getView')
            ->will($this->returnValue($this->view));

        $this->view->expects($this->once())
            ->method('setTemplate')
            ->with($this->controller->getTemplatePrefix() . '/notes/form');

        $this->controller->expects($this->once())
            ->method('renderView')
            ->with($this->equalTo($this->view));

        $this->controller->addAction();
    }

    public function testEditAction()
    {
        $id = 1;
        $note = [
            'comment' => 'comment',
            'id' => $id,
            'priority' => 'Y',
            'version' => 1
        ];

        $this->getFromRoute(0, 'id', $id);

        $this->controller->expects($this->once())
            ->method('makeRestCall')
        ->will($this->returnValue($note));

        $this->controller->expects($this->once())
            ->method('generateFormWithData')
        ->will($this->returnValue($this->getEditForm()));

        $this->controller->expects($this->once())
            ->method('getView')
            ->will($this->returnValue($this->view));

        $this->view->expects($this->once())
            ->method('setTemplate')
            ->with($this->controller->getTemplatePrefix() . '/notes/form');

        $this->controller->expects($this->once())
            ->method('renderView')
            ->with($this->equalTo($this->view));

        $this->controller->editAction();
    }

    /**
     * Tests the process add notes function
     *
     * @dataProvider processAddNotesProvider
     *
     * @param array $data
     */
    public function testProcessAddNotes($data)
    {
        $this->controller->expects($this->once())
            ->method('getLoggedInUser')
            ->will($this->returnValue(1));

        $this->controller->expects($this->once())
            ->method('processAdd')
            ->will($this->returnValue(['id' => 1]));

        $this->getRedirectToIndex();

        $this->controller->processAddNotes($data);
    }

    /**
     * Tests the process add notes function redirects properly on failure
     *
     * @dataProvider processAddNotesProvider
     *
     * @param array $data
     */
    public function testProcessAddNotesFail($data)
    {
        $this->controller->expects($this->once())
            ->method('getLoggedInUser')
            ->will($this->returnValue(1));

        $this->controller->expects($this->once())
            ->method('processAdd')
            ->will($this->returnValue([]));

        $this->controller->expects($this->once())
            ->method('redirectToRoute')
            ->with(
                $this->equalTo($this->controller->getRoutePrefix() . '/add-note'),
                $this->equalTo(['action' => 'Add']),
                $this->equalTo([]),
                $this->equalTo(true)
            );

        $this->controller->processAddNotes($data);
    }

    /**
     * Tests the process add notes function throws a bad request
     * exception when the linkedId is missing
     *
     * @expectedException \Common\Exception\BadRequestException
     */
    public function testProcessAddNotesMissingLinkException()
    {
        $data = $this->getTestFormPost('note_t_app', null, null, null);

        $this->controller->expects($this->once())
            ->method('getLoggedInUser')
            ->will($this->returnValue(1));

        $this->controller->processAddNotes($data);
    }

    /**
     * Data provider for process add notes
     */
    public function processAddNotesProvider()
    {
        return [
            [$this->getTestFormPost('note_t_lic'), 'licence'],
            [$this->getTestFormPost('note_t_app'), 'application'],
            [$this->getTestFormPost('note_t_irfo_gv'), 'irfoGvPermit'],
            [$this->getTestFormPost('note_t_irfo_psv'), 'irfoPsvAuth'],
            [$this->getTestFormPost('note_t_case'), 'case'],
            [$this->getTestFormPost('note_t_bus'), 'busReg']
        ];
    }

    public function testProcessEditNotes()
    {
        $loggedInUser = 1;
        $data = $this->getTestFormPost('', 1, 1);
        $processedData = $this->getTestProcessedFormPost(1, 1, $loggedInUser);

        $this->controller->expects($this->once())
            ->method('getLoggedInUser')
            ->will($this->returnValue($loggedInUser));

        $this->controller->expects($this->once())
            ->method('processEdit')
            ->with(
                $this->equalTo($processedData),
                $this->equalTo('Note')
            )
            ->will($this->returnValue([]));

        $this->getRedirectToIndex();

        $this->controller->processEditNotes($data);
    }

    public function testProcessEditNotesFail()
    {
        $loggedInUser = 1;
        $data = $this->getTestFormPost('', 1, 1);
        $processedData = $this->getTestProcessedFormPost(1, 1, $loggedInUser);

        $this->controller->expects($this->once())
            ->method('getLoggedInUser')
            ->will($this->returnValue($loggedInUser));

        $this->controller->expects($this->once())
            ->method('processEdit')
            ->with(
                $this->equalTo($processedData),
                $this->equalTo('Note')
            )
            ->will($this->returnValue(['failed']));

        $this->controller->expects($this->once())
            ->method('redirectToRoute')
            ->with(
                $this->equalTo($this->controller->getRoutePrefix() . '/modify-note'),
                $this->equalTo(['action' => 'Edit']),
                $this->equalTo([]),
                $this->equalTo(true)
            );

        $this->controller->processEditNotes($data);
    }

    /**
     * Tests the output of getIdField()
     *
     * @dataProvider getIdFieldProvider
     *
     * @param $refDataKey
     * @param $expectedOutput
     */
    public function testGetIdField($refDataKey, $expectedOutput)
    {
        $this->assertEquals($this->controller->getIdField($refDataKey), $expectedOutput);
    }

    /**
     * Data provider got testGetIdField
     *
     * @return array
     */
    public function getIdFieldProvider()
    {
        return [
            ['note_t_lic',
                [
                    'field' => 'empty', //deliberately doesn't show licence id in lists
                    'displayId' => 'id',
                    'id' => 'id'
                ]
            ],
            ['note_t_app',
                [
                    'field' => 'application',
                    'displayId' => 'id',
                    'id' => 'id'
                ]
            ],
            ['note_t_irfo_gv',
                [
                    'field' => 'irfoGvPermit',
                    'displayId' => 'id',
                    'id' => 'id'
                ]
            ],
            ['note_t_irfo_psv',
                [
                    'field' => 'irfoPsvAuth',
                    'displayId' => 'id',
                    'id' => 'id'
                ]
            ],
            ['note_t_case',
                [
                    'field' => 'case',
                    'displayId' => 'id',
                    'id' => 'id'
                ]
            ],
            ['note_t_bus',
                [
                    'field' => 'busReg',
                    'displayId' => 'routeNo',
                    'id' => 'id'
                ]
            ]
        ];
    }

    public function testDeleteServiceName()
    {
        $this->assertEquals('Note', $this->controller->getDeleteServiceName());
    }

    public function getRedirectToIndex()
    {
        $this->controller->expects($this->once())
            ->method('redirectToRoute')
            ->with(
                $this->equalTo($this->controller->getRoutePrefix() . $this->controller->getRedirectIndexRoute()),
                $this->equalTo(['action'=>'index', 'id' => null]),
                $this->equalTo(['code' => '303']),
                $this->equalTo(true)
            );
    }

    public function getTestFormPost($refDataNoteType, $id = null, $version = null, $linkedId = 1)
    {
        return [
            'main' => [
                'comment' => 'the comment',
                'priority' => 'Y'
            ],
            'id' => $id,
            'licence' => 7,
            'noteType' => $refDataNoteType,
            'linkedId' => $linkedId,
            'case' => 28,
            'version' => $version
        ];
    }

    public function getTestProcessedFormPost($id = null, $version = null, $loggedInUser = 1)
    {
        return [
            'main' => [
                'comment' => 'the comment',
                'priority' => 'Y'
            ],
            'id' => $id,
            'version' => $version,
            'priority' => 'Y',
            'lastModifiedBy' => $loggedInUser
        ];
    }

    private function getSampleResult()
    {
        return [
            'Results' => [
                0 => [
                    'noteType' => [
                        'id' => 'note_t_lic',
                        'description' => 'Licence'
                    ]
                ]
            ]
        ];
    }

    private function getEditForm()
    {
        $formMock = $this->getMock('\Zend\Form\Form', ['get']);
        $getElementMock = $this->getMock('\Zend\Form\Fieldset', ['get']);
        $setAttributeMock = $this->getMock('\Zend\Form\Element', ['setAttribute']);

        $setAttributeMock->expects($this->once())
            ->method('setAttribute');

        $getElementMock->expects($this->once())
            ->method('get')
            ->with('comment')
            ->will($this->returnValue($setAttributeMock));

        $formMock->expects($this->once())
            ->method('get')
            ->with('main')
            ->will($this->returnValue($getElementMock));

        return $formMock;
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
                ->method('getFromRoute')
                ->with($this->equalTo($with))
                ->will($this->returnValue($will));
        } else {
            $this->controller->expects($this->at($at))
                ->method('getFromRoute')
                ->with($this->equalTo($with));
        }
    }

    /**
     * Generate a fromPost function call
     *
     * @param int $at
     * @param mixed $with
     * @param mixed $will
     */
    private function getFromPost($at, $with, $will = false)
    {
        if ($will) {
            $this->controller->expects($this->at($at))
                ->method('getFromPost')
                ->with($this->equalTo($with))
                ->will($this->returnValue($will));
        } else {
            $this->controller->expects($this->at($at))
                ->method('getFromPost')
                ->with($this->equalTo($with));
        }
    }
}
