<?php
namespace OlcsTest\Controller\Conviction;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OlcsTest\Bootstrap;
use Mockery as m;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;

/**
 * Conviction controller form post tests
 *
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
class ConvictionControllerTest extends AbstractHttpControllerTestCase
{

    public function setUp()
    {
        $this->sut = new \Olcs\Controller\Cases\Conviction\ConvictionController();

        $routeMatch = new RouteMatch(array('controller' => 'conviction'));
        $this->event      = new MvcEvent();
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($routeMatch);
        $this->sut->setEvent($this->event);

        parent::setUp();
    }

    public function testSaveDefendantTypeOperator()
    {
        $service = 'Conviction';
        $data = [
            'id' => 1,
            'defendantType' => 'def_t_op',
            'licence' => [
                'organisation' => [
                    'name' => 'some operator'
                ]
            ]
        ];

        $caseId = 1;
        $case = [
            'id' => 99,
            'licence' => [
                'organisation' => [
                    'name' => 'some operator'
                ]
            ]
        ];

        $mockRestHelper = m::mock('RestHelper');
        // save conviction
        $mockRestHelper->shouldReceive('makeRestCall')->with(
            'Conviction',
            'PUT',
            m::type('array'),
            ""
        );

        $mockCaseService = m::mock('Olcs\Service\Data\Cases');
        $mockCaseService->shouldReceive('fetchCaseData')->with($caseId)->andReturn($case);

        $mockServiceManager = m::mock('\Zend\ServiceManager\ServiceManager');
        $mockServiceManager->shouldReceive('get')->with('HelperService')->andReturnSelf();
        $mockServiceManager->shouldReceive('get')->with('DataServiceManager')->andReturnSelf();
        $mockServiceManager->shouldReceive('get')->with('Olcs\Service\Data\Cases')->andReturn($mockCaseService);
        $mockServiceManager->shouldReceive('getHelperService')->with('RestHelper')->andReturn($mockRestHelper);
        $mockServiceManager->shouldReceive('get->getHelperService')->with('RestService')->andReturn($mockRestHelper);

        $this->sut->getEvent()->getRouteMatch()->setParam('case', $caseId);

        $this->sut->setServiceLocator($mockServiceManager);

        $result = $this->sut->save($data, $service);

        $this->assertNull($result);
    }
}
