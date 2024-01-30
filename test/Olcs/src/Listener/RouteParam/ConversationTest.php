<?php

declare(strict_types=1);

namespace OlcsTest\Listener\RouteParam;

use Common\Service\Cqrs\Query\CachingQueryService as QueryService;
use Common\Service\Helper\UrlHelperService;
use Dvsa\Olcs\Transfer\Query\QueryContainerInterface;
use Dvsa\Olcs\Transfer\Query\Search\Licence;
use Dvsa\Olcs\Transfer\Util\Annotation\AnnotationBuilder;
use Interop\Container\ContainerInterface;
use Laminas\EventManager\Event;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Http\Response;
use Laminas\Navigation\AbstractContainer;
use Laminas\Navigation\Page\AbstractPage;
use Laminas\View\Helper\Navigation;
use Laminas\View\HelperPluginManager;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParam\Conversation;
use Mockery as m;
use Olcs\Listener\RouteParams;
use Common\RefData;
use Laminas\View\Model\ViewModel;
use Laminas\View\Helper\Url;

class ConversationTest extends TestCase
{
    protected Conversation $sut;

    public function setUp(): void
    {
        $this->sut = m::mock(Conversation::class)
                      ->makePartial()
                      ->shouldAllowMockingProtectedMethods();
    }

    public function testAttach(): void
    {
        $mockEventManager = m::mock(EventManagerInterface::class);
        $mockEventManager->shouldReceive('attach')
                         ->once()
                         ->with(
                             RouteParams::EVENT_PARAM . 'licence',
                             [$this->sut, 'onConversation'],
                             1,
                         );

        $this->sut->attach($mockEventManager);
    }

    public function testOnConversation(): void
    {
        $mockAnnotationBuilder = m::mock(AnnotationBuilder::class);
        $mockQueryService = m::mock(QueryService::class);
        $mockAbstractPage = m::mock(AbstractPage::class);
        $mockNavigationPlugin = m::mock(AbstractContainer::class);
        $mockRouteParam = m::mock(RouteParam::class);
        $mockQuery = m::mock(QueryContainerInterface::class);
        $mockResponse = m::mock(Response::class);

        // Declaration of Mockery_4_Laminas_View_Helper_Navigation::__call($method, array $args) should be
        // compatible with Laminas\View\Helper\Navigation::__call($method, array $arguments = Array)
        $er = error_reporting();
        error_reporting(0);
        $mockNavigation = m::mock(Navigation::class)->makePartial();
        error_reporting($er);

        $mockRouteParam->shouldReceive('getValue')
                       ->twice()
                       ->andReturn('7');

        $mockAnnotationBuilder->shouldReceive('createQuery')
                              ->twice()
                              ->withArgs(
                                  function ($licence) {
                                      $this->assertInstanceOf(Licence::class, $licence);
                                      $this->assertEquals(7, $licence->getId());

                                      return true;
                                  },
                              )->andReturn($mockQuery);

        $mockQueryService->shouldReceive('send')
                         ->twice()
                         ->with($mockQuery)
                         ->andReturn($mockResponse);

        $mockResponse->shouldReceive('isOk')
                     ->twice()
                     ->andReturnTrue();

        $mockAbstractPage->shouldReceive('setVisible')
                         ->twice()
                         ->with(false);

        $mockNavigation->shouldReceive('__invoke')
                       ->twice()
                       ->with('navigation')
                       ->andReturn($mockNavigationPlugin);

        $sut = new Conversation();
        $sut->setNavigationPlugin($mockNavigation);
        $sut->setQueryService($mockQueryService);
        $sut->setAnnotationBuilder($mockAnnotationBuilder);

        $e = m::mock(EventInterface::class);
        $e->shouldReceive('getTarget')
          ->twice()
          ->andReturn($mockRouteParam);

        $result = [
            'organisation' => [
                'isMessagingDisabled' => false,
            ],
        ];
        $mockResponse->shouldReceive('getResult')
                     ->once()
                     ->andReturn($result);
        $mockNavigationPlugin->shouldReceive('findBy')
                             ->once()
                             ->with('id', 'conversation_list_enable_messaging')
                             ->andReturn($mockAbstractPage);
        $sut->onConversation($e);

        $result = [
            'organisation' => [
                'isMessagingDisabled' => true,
            ],
        ];
        $mockResponse->shouldReceive('getResult')
                     ->once()
                     ->andReturn($result);
        $mockNavigationPlugin->shouldReceive('findBy')
                             ->once()
                             ->with('id', 'conversation_list_disable_messaging')
                             ->andReturn($mockAbstractPage);
        $sut->onConversation($e);
    }

    public function testInvoke()
    {
        $mockAnnotationBuilder = m::mock(AnnotationBuilder::class);
        $mockQueryService = m::mock(QueryService::class);
        $mockHelperPluginManager = m::mock(ContainerInterface::class);

        // Declaration of Mockery_4_Laminas_View_Helper_Navigation::__call($method, array $args) should be
        // compatible with Laminas\View\Helper\Navigation::__call($method, array $arguments = Array)
        $er = error_reporting();
        error_reporting(0);
        $mockNavigation = m::mock(Navigation::class)->makePartial();
        error_reporting($er);

        $mockSl = m::mock(ContainerInterface::class);
        $mockSl->shouldReceive('get')
               ->once()
               ->with(AnnotationBuilder::class)
               ->andReturn($mockAnnotationBuilder);
        $mockSl->shouldReceive('get')
               ->once()
               ->with(QueryService::class)
               ->andReturn($mockQueryService);
        $mockSl->shouldReceive('get')
               ->once()
               ->with(HelperPluginManager::class)
               ->andReturn($mockHelperPluginManager);

        $mockHelperPluginManager->shouldReceive('get')
                                ->once()
                                ->with(Navigation::class)
                                ->andReturn($mockNavigation);

        $sut = new Conversation();
        $service = $sut->__invoke($mockSl, Conversation::class);

        $this->assertSame($sut, $service);
        $this->assertSame($mockAnnotationBuilder, $sut->getAnnotationBuilder());
        $this->assertSame($mockQueryService, $sut->getQueryService());
        $this->assertSame($mockNavigation, $sut->getNavigationPlugin());
    }
}
