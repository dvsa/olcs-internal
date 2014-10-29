<?php

namespace OlcsTest\Listener;

use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use Mockery as m;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Zend\Mvc\MvcEvent;

/**
 * Class RouteParamsTest
 * @package OlcsTest\Listener
 */
class RouteParamsTest extends TestCase
{
    public function testAttach()
    {
        $sut = new RouteParams();

        $mockEventManager = m::mock('Zend\EventManager\EventManagerInterface');
        $mockEventManager->shouldReceive('attach')->once()
            ->with(MvcEvent::EVENT_DISPATCH, [$sut, 'onDispatch'], 20);

        $sut->attach($mockEventManager);
    }

    public function testOnDispatch()
    {
        $params = ['test' => 'value'];

        $mockEvent = m::mock('Zend\Mvc\MvcEvent');
        $mockEvent->shouldReceive('getRouteMatch->getParams')->andReturn($params);

        $sut = new RouteParams();

        $matcher = function ($item) use ($params, $sut) {
            if (!($item instanceof RouteParam)) {
                return false;
            }
            if ($item->getValue() != 'value' || $item->getContext() != $params || $item->getTarget() != $sut) {
                return false;
            }

            return true;
        };

        $mockEventManager = m::mock('Zend\EventManager\EventManagerInterface');
        $mockEventManager->shouldIgnoreMissing();
        $mockEventManager->shouldReceive('trigger')->with(RouteParams::EVENT_PARAM . 'test', m::on($matcher))->once();

        $sut->setEventManager($mockEventManager);

        $sut->onDispatch($mockEvent);
    }
}
 