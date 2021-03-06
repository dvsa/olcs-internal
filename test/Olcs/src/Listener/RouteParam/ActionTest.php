<?php

namespace OlcsTest\Listener\RouteParam;

use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParam\Action;
use Mockery as m;
use Olcs\Listener\RouteParams;

/**
 * Class ActionTest
 * @package OlcsTest\Listener\RouteParam
 */
class ActionTest extends TestCase
{
    public function testAttach()
    {
        $sut = new Action();

        $mockEventManager = m::mock('Laminas\EventManager\EventManagerInterface');
        $mockEventManager->shouldReceive('attach')->once()
            ->with(RouteParams::EVENT_PARAM . 'action', [$sut, 'onAction'], 1);

        $sut->attach($mockEventManager);
    }

    public function testOnAction()
    {
        $action = 'add';

        $event = new RouteParam();
        $event->setValue($action);

        $mockRouter = m::mock('Laminas\Mvc\Router\RouteStackInterface');
        $mockRouter->shouldReceive('assemble')
            ->with(['action' => $action])
            ->andReturn('http://anything/');

        $mockContainer = m::mock('Laminas\View\Helper\Placeholder\Container');
        $mockContainer->shouldReceive('set')->with($action);

        $mockPlaceholder = m::mock('Laminas\View\Helper\Placeholder');
        $mockPlaceholder->shouldReceive('getContainer')->with('action')->andReturn($mockContainer);

        $mockViewHelperManager = m::mock('Laminas\View\HelperPluginManager');
        $mockViewHelperManager->shouldReceive('get')->with('placeholder')->andReturn($mockPlaceholder);

        $sut = new Action();
        $sut->setViewHelperManager($mockViewHelperManager);

        $sut->onAction($event);
    }

    public function testCreateService()
    {
        $mockViewHelperManager = m::mock('Laminas\View\HelperPluginManager');

        $mockSl = m::mock('Laminas\ServiceManager\ServiceLocatorInterface');
        $mockSl->shouldReceive('get')->with('ViewHelperManager')->andReturn($mockViewHelperManager);

        $sut = new Action();
        $service = $sut->createService($mockSl);

        $this->assertSame($sut, $service);
        $this->assertSame($mockViewHelperManager, $sut->getViewHelperManager());
    }
}
