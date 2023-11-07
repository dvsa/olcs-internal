<?php

namespace Olcs\Listener\RouteParam;

use Interop\Container\ContainerInterface;
use Olcs\Event\RouteParam;
use Olcs\Listener\RouteParams;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Common\View\Helper\PluginManagerAwareTrait as ViewHelperManagerAwareTrait;

class Action implements ListenerAggregateInterface, FactoryInterface
{
    use ListenerAggregateTrait;
    use ViewHelperManagerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            RouteParams::EVENT_PARAM . 'action',
            [$this, 'onAction'],
            $priority
        );
    }

    /**
     * @param RouteParam $e
     */
    public function onAction(RouteParam $e)
    {
        $placeholder = $this->getViewHelperManager()->get('placeholder');
        $placeholder->getContainer('action')->set($e->getValue());
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return Action
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) : Action
    {
        $this->setViewHelperManager($container->get('ViewHelperManager'));
        return $this;
    }
}
