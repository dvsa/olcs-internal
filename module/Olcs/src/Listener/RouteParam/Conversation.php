<?php

declare(strict_types=1);

namespace Olcs\Listener\RouteParam;

use Common\Exception\ResourceNotFoundException;
use Common\Service\Cqrs\Query\CachingQueryService;
use Common\Service\Cqrs\Query\CachingQueryService as QueryService;
use Dvsa\Olcs\Transfer\Query\Search\Licence as LicenceQuery;
use Dvsa\Olcs\Transfer\Util\Annotation\AnnotationBuilder;
use Interop\Container\ContainerInterface;
use Laminas\EventManager\Event;
use Laminas\EventManager\EventInterface;
use Laminas\View\HelperPluginManager;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\Navigation\AbstractContainer;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Helper\Navigation;

class Conversation implements ListenerAggregateInterface, FactoryInterface
{
    use ListenerAggregateTrait;

    private AbstractContainer $sidebarNavigationService;
    private AnnotationBuilder $annotationBuilder;
    private QueryService      $queryService;
    private Navigation        $navigationPlugin;

    /** @param int $priority */
    public function attach(EventManagerInterface $events, $priority = 1): void
    {
        $this->listeners[] = $events->attach(
            RouteParams::EVENT_PARAM . 'licence',
            [$this, 'onConversation'],
            $priority,
        );
    }

    public function onConversation(EventInterface $e): void
    {
        $routeParam = $e->getTarget();

        $licence = $this->getLicence((int)$routeParam->getValue());
        $isMessagingDisabled = $licence['organisation']['isMessagingDisabled'];

        /** @var AbstractContainer $navigationPlugin */
        $navigationPlugin = $this->navigationPlugin->__invoke('navigation');

        if ($isMessagingDisabled) {
            $navigationPlugin->findById('conversation_list_disable_messaging')->setVisible(false);
        } else {
            $navigationPlugin->findById('conversation_list_enable_messaging')->setVisible(false);
        }
    }

    private function getLicence(int $id): array
    {
        $query = $this->annotationBuilder->createQuery(LicenceQuery::create(['id' => $id]));
        $response = $this->queryService->send($query);

        if (!$response->isOk()) {
            throw new ResourceNotFoundException("Licence id [$id] not found");
        }

        return $response->getResult();
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Conversation
    {
        $this->annotationBuilder = $container->get('TransferAnnotationBuilder');
        $this->queryService = $container->get('QueryService');
        $this->navigationPlugin = $container->get('ViewHelperManager')->get('Navigation');
        return $this;
    }
}
