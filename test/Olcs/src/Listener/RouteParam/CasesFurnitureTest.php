<?php

/**
 * Cases Furniture Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace OlcsTest\Listener\RouteParam;

use Common\Service\Cqrs\Command\CommandSender;
use Common\Service\Cqrs\Query\QuerySender;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParam\CasesFurniture;
use Olcs\Listener\RouteParams;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery as m;
use Laminas\View\Helper\Url;
use Laminas\View\Model\ViewModel;

/**
 * Cases Furniture Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class CasesFurnitureTest extends MockeryTestCase
{
    /**
     * @var CasesFurniture
     */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new CasesFurniture();
    }

    public function setupMockCase($id, $data)
    {
        $mockQuerySender  = m::mock(QuerySender::class);

        $mockResult = m::mock();
        $mockResult->shouldReceive('isOk')->with()->once()->andReturn(true);
        $mockResult->shouldReceive('getResult')->with()->once()->andReturn($data);

        $mockQuerySender->shouldReceive('send')->once()->andReturn($mockResult);

        $this->sut->setQuerySender($mockQuerySender);
    }

    public function testAttach()
    {
        $mockEventManager = m::mock('Laminas\EventManager\EventManagerInterface');
        $mockEventManager->shouldReceive('attach')->once()
            ->with(RouteParams::EVENT_PARAM . 'case', [$this->sut, 'onCase'], 1);

        $this->sut->attach($mockEventManager);
    }

    public function testOnCase()
    {
        $id = 69;
        $case = [
            'id' => $id,
            'application' => [
                'id' => 100,
            ],
            'licence' => [
                'id' => 101,
                'licNo' => 'AB12345'
            ],
            'transportManager' => [
                'id' => 102,
                'homeCd' => [
                    'person' => [
                        'forename' => 'Bob',
                        'familyName' => 'Smith'
                    ]
                ]
            ],
            'tmDecisions' => [
                ['id' => 200]
            ]
        ];

        $event = new RouteParam();
        $event->setValue($id);

        $this->setupMockCase($id, $case);

        $mockPlaceholder = m::mock()
            ->shouldReceive('getContainer')->once()
            ->with('status')
            ->andReturn(
                m::mock()->shouldReceive('set')->once()->getMock()
            )
            ->shouldReceive('getContainer')->once()
            ->with('horizontalNavigationId')
            ->andReturn(
                m::mock()->shouldReceive('set')->once()->with('case')->getMock()
            )
            ->shouldReceive('getContainer')->once()
            ->with('right')
            ->andReturn(
                m::mock()->shouldReceive('set')->once()
                    ->with(m::type(ViewModel::class))
                    ->andReturnUsing(
                        function ($view) {
                            $this->assertEquals('sections/cases/partials/right', $view->getTemplate());
                        }
                    )
                    ->getMock()
            )
            ->shouldReceive('getContainer')->once()
            ->with('pageTitle')
            ->andReturn(
                m::mock()->shouldReceive('set')->once()
                    ->with('<a href="tm">Bob Smith</a> / <a href="lic">AB12345</a> / <a href="app">100</a> / Case 69')
                    ->getMock()
            )
            ->getMock();

        $mockViewHelperManager = m::mock('\Laminas\View\HelperPluginManager')
            ->shouldReceive('get')->once()->with('placeholder')->andReturn($mockPlaceholder)
            ->shouldReceive('get')->once()->with('url')->andReturn(
                m::mock(Url::class)
                    ->shouldReceive('__invoke')
                    ->once()
                    ->with('lva-application/case', ['application' => 100], [], true)
                    ->andReturn('app')
                    ->shouldReceive('__invoke')
                    ->once()
                    ->with('licence/cases', ['licence' => 101], [], true)
                    ->andReturn('lic')
                    ->shouldReceive('__invoke')
                    ->once()
                    ->with('transport-manager/details', ['transportManager' => 102], [], true)
                    ->andReturn('tm')
                    ->getMock()
            )
            ->getMock();

        $this->sut->setViewHelperManager($mockViewHelperManager);

        $this->sut->onCase($event);
    }

    public function testCreateService()
    {
        $mockViewHelperManager = m::mock('Laminas\View\HelperPluginManager');
        $mockQuerySender = m::mock(QuerySender::class);
        $mockCommandSender = m::mock(CommandSender::class);

        $mockSl = m::mock('Laminas\ServiceManager\ServiceLocatorInterface');
        $mockSl->shouldReceive('get')->with('ViewHelperManager')->andReturn($mockViewHelperManager);
        $mockSl->shouldReceive('get')->with('QuerySender')->andReturn($mockQuerySender);
        $mockSl->shouldReceive('get')->with('CommandSender')->andReturn($mockCommandSender);

        $service = $this->sut->createService($mockSl);

        $this->assertSame($this->sut, $service);
        $this->assertSame($mockViewHelperManager, $this->sut->getViewHelperManager());
        $this->assertSame($mockQuerySender, $this->sut->getQuerySender());
        $this->assertSame($mockCommandSender, $this->sut->getCommandSender());
    }

    public function testOnCaseNotFound()
    {
        $this->expectException(\Common\Exception\ResourceNotFoundException::class);

        $id = 69;

        $event = new RouteParam();
        $event->setValue($id);

        $mockQuerySender  = m::mock(QuerySender::class);

        $mockResult = m::mock();
        $mockResult->shouldReceive('isOk')->with()->once()->andReturn(false);

        $mockQuerySender->shouldReceive('send')->once()->andReturn($mockResult);

        $this->sut->setQuerySender($mockQuerySender);

        $this->sut->onCase($event);
    }

    /**
     * @dataProvider getStatusArrayProvider
     */
    public function testGetStatusArray($case, $expected)
    {
        $method = new \ReflectionMethod($this->sut, 'getStatusArray');
        $method->setAccessible(true);

        $this->assertEquals($expected, $method->invoke($this->sut, $case));
    }

    public function getStatusArrayProvider()
    {
        return [
            // open
            [
                [
                    'closedDate' => null
                ],
                [
                    'colour' => 'Orange',
                    'value' => 'Open',
                ],
            ],
            // closed
            [
                [
                    'closedDate' => '2015-01-02'
                ],
                [
                    'colour' => 'Grey',
                    'value' => 'Closed',
                ],
            ],
        ];
    }
}
