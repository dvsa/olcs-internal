<?php

namespace OlcsTest\Controller\Traits;

use Common\Service\Helper\FormHelperService;
use Dvsa\Olcs\Utils\Constants\FilterOptions;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery as m;
use Olcs\Service\Data\SubCategory as SubCategoryDS;

/**
 * @covers \Olcs\Controller\Traits\TaskSearchTrait
 */
class TaskSearchTraitTest extends MockeryTestCase
{
    const ID = 99999;
    const TEAM_ID = 8888;

    /** @var \OlcsTest\Controller\Traits\Stub\StubTaskSearchTrait */
    private $sut;

    public function setUp(): void
    {
        $this->sut = m::mock(\OlcsTest\Controller\Traits\Stub\StubTaskSearchTrait::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods(true);
    }

    public function testUpdateSelectValueOptions()
    {
        $options = [
            'exists1' => 'exists1_Val',
            'exists2' => 'exists2_Val',
            'exists3' => 'exists3_Val',
        ];

        $changeOptions =
            [
                'exists2' => 'exists2_NewVal',
                'new1' => 'new Val',
                'exists1' => null,
                'new2' => '',
                'new3' => null,
            ];

        $mockEl = m::mock(\Zend\Form\Element\Select::class);
        $mockEl->shouldReceive('getValueOptions')->once()->andReturn($options);
        $mockEl->shouldReceive('setValueOptions')
            ->once()
            ->andReturnUsing(
                function ($arg) {
                    static::assertEquals(
                        [
                            'exists2' => 'exists2_NewVal',
                            'exists3' => 'exists3_Val',
                            'new1' => 'new Val',
                            'new2' => '',
                        ],
                        $arg
                    );

                    return $this;
                }
            );

        $this->sut->traitUpdateSelectValueOptions($mockEl, $changeOptions);
    }

    /**
     * @dataProvider dpTestMapTaskFilters
     */
    public function testMapTaskFilters($extra, $expect)
    {
        $userData = [
            'id' => self::ID,
            'team' => [
                'id' => self::TEAM_ID,
            ],
        ];

        $mockUser = m::mock(\Zend\Http\Request::class)
            ->shouldReceive('getUserData')->once()->andReturn($userData)
            ->getMock();

        $mockRequest = m::mock(\Zend\Http\Request::class);
        $mockRequest->shouldReceive('getQuery->toArray')->once()->andReturn(['query' => 'unit_Query']);

        $this->sut
            ->shouldReceive('getRequest')->once()->andReturn($mockRequest)
            ->shouldReceive('currentUser')->once()->andReturn($mockUser);

        static::assertEquals(
            $expect,
            $this->sut->traitMapTaskFilters($extra)
        );
    }

    public function dpTestMapTaskFilters()
    {
        return [
            [
                'extra' => [
                    'assignedToUser' => null,
                    'assignedToTeam' => null,
                    'date' => 'unit_Date',
                    'status' => 'unit_Status',
                    'sort' => null,
                    'order' => '',
                    'page' => 0,
                    'showTasks' => '0',
                    'newKey' => 'unit_NewKey',
                ],
                'expect' =>
                    [
                        'date' => 'unit_Date',
                        'status' => 'unit_Status',
                        'limit' => 10,
                        'newKey' => 'unit_NewKey',
                        'query' => 'unit_Query',
                    ],
            ],
        ];
    }

    public function testGetTaskForm()
    {
        $expectCategory = 8001;
        $expectTeam = 9001;
        $filters = [
            'assignedToTeam' => $expectTeam,
            'category' => $expectCategory,
        ];

        $listData = [
            'assignedToTeam' => ['unit_ListDataTeam'],
            'assignedToUser' => ['unit_ListDataUser'],
        ];

        $mockShowField = m::mock(\Zend\Form\Element::class);
        $mockShowField->shouldReceive('setValueOptions')->once()->with(m::hasKey(FilterOptions::SHOW_ALL));

        $mockTeamField = m::mock(\Zend\Form\Element\Select::class);
        $mockTeamField->shouldReceive('setValueOptions')->once()->with($listData['assignedToTeam']);
        $mockUserField = m::mock(\Zend\Form\Element\Select::class);
        $mockUserField->shouldReceive('setValueOptions')->once()->with($listData['assignedToUser']);

        $mockForm = m::mock(\Zend\Form\FormInterface::class)->makePartial()
            ->shouldReceive('setData')->once()->with($filters)
            ->shouldReceive('get')->once()->with('assignedToTeam')->andReturn($mockTeamField)
            ->shouldReceive('get')->once()->with('assignedToUser')->andReturn($mockUserField)
            ->shouldReceive('get')->once()->with('showTasks')->andReturn($mockShowField)
            ->getMock();

        $mockReq = m::mock(\Zend\Http\Request::class);

        $mockSubCatDs = m::mock(SubCategoryDS::class)
            ->shouldReceive('setCategory')->once()->with($expectCategory)
            ->getMock();

        $mockFormHlpr = m::mock(FormHelperService::class)
            ->shouldReceive('createForm')->once()->with('TasksHome', false)->andReturn($mockForm)
            ->shouldReceive('setFormActionFromRequest')->once()->with($mockForm, $mockReq)->andReturnSelf()
            ->getMock();

        $mockSm = m::mock(\Zend\Di\ServiceLocatorInterface::class)
            ->shouldReceive('get')->with(SubCategoryDS::class)->once()->andReturn($mockSubCatDs)
            ->shouldReceive('get')->with('Helper\Form')->once()->andReturn($mockFormHlpr)
            ->getMock();

        //  call
        /** @var \OlcsTest\Controller\Traits\Stub\StubTaskSearchTrait $sut */
        $this->sut
            ->shouldReceive('getServiceLocator')->once()->andReturn($mockSm)
            ->shouldReceive('getRequest')->once()->andReturn($mockReq)
            ->shouldReceive('getListDataTeam')->once()->andReturn($listData['assignedToTeam'])
            ->shouldReceive('getListDataUser')->once()->with($expectTeam, 'All')->andReturn($listData['assignedToUser'])
            ->getMock();

        $this->sut->traitGetTaskForm($filters);
    }

    /**
     * @dataProvider dpTestProcessTasksActions
     */
    public function testProcessTasksActions($type, $action, $id, $paramName, $expected)
    {
        $query = ['query' => 'unit_Query'];

        $mockRequest = m::mock(\Zend\Http\Request::class);
        $mockRequest->shouldReceive('isPost')->once()->andReturn(true);
        $mockRequest->shouldReceive('getQuery->toArray')->once()->andReturn($query);

        $mockParams = m::mock();
        $mockParams->shouldReceive('fromPost')->with('action')->andReturn($action);
        $mockParams->shouldReceive('fromPost')->with('id')->andReturn($id);

        $this->sut
            ->shouldReceive('getRequest')->andReturn($mockRequest)
            ->shouldReceive('params')->withNoArgs()->andReturn($mockParams)
            ->shouldReceive('params')->with($paramName)->andReturn(200)
            ->shouldReceive('redirect->toRoute')
            ->with('task_action', $expected, ['query' => $query])
            ->andReturn('REDIR');

        $this->assertEquals(
            'REDIR',
            $this->sut->traitProcessTasksActions($type)
        );
    }

    public function dpTestProcessTasksActions()
    {
        return [
            [
                'type' => '',
                'action' => 'create task',
                'id' => null,
                'paramName' => '',
                'expected' => ['action' => 'add']
            ],
            [
                'type' => '',
                'action' => 'edit',
                'id' => [100],
                'paramName' => '',
                'expected' => ['action' => 'edit', 'task' => 100]
            ],
            [
                'type' => '',
                'action' => 're-assign task',
                'id' => [100, 101],
                'paramName' => '',
                'expected' => ['action' => 'reassign', 'task' => '100-101']
            ],
            [
                'type' => '',
                'action' => 'close task',
                'id' => [100, 101],
                'paramName' => '',
                'expected' => ['action' => 'close', 'task' => '100-101']
            ],
            [
                'type' => 'organisation',
                'action' => 'add',
                'id' => null,
                'paramName' => 'organisation',
                'expected' => ['type' => 'organisation', 'typeId' => 200, 'action' => 'add']
            ],
            [
                'type' => 'licence',
                'action' => 'add',
                'id' => null,
                'paramName' => 'licence',
                'expected' => ['type' => 'licence', 'typeId' => 200, 'action' => 'add']
            ],
            [
                'type' => 'application',
                'action' => 'add',
                'id' => null,
                'paramName' => 'application',
                'expected' => ['type' => 'application', 'typeId' => 200, 'action' => 'add']
            ],
            [
                'type' => 'transportManager',
                'action' => 'add',
                'id' => null,
                'paramName' => 'transportManager',
                'expected' => ['type' => 'tm', 'typeId' => 200, 'action' => 'add']
            ],
            [
                'type' => 'busReg',
                'action' => 'add',
                'id' => null,
                'paramName' => 'busRegId',
                'expected' => ['type' => 'busreg', 'typeId' => 200, 'action' => 'add']
            ],
            [
                'type' => 'case',
                'action' => 'add',
                'id' => null,
                'paramName' => 'case',
                'expected' => ['type' => 'case', 'typeId' => 200, 'action' => 'add']
            ],
            [
                'type' => 'irhpapplication',
                'action' => 'add',
                'id' => null,
                'paramName' => 'irhpAppId',
                'expected' => ['type' => 'irhpapplication', 'typeId' => 200, 'action' => 'add']
            ],
        ];
    }
}
