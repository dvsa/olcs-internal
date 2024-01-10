<?php

namespace Olcs\Service\Marker;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Service\AbstractPluginManagerFactory;

/**
 * MarkerPluginManagerFactory
 *
 * @author Mat Evans <mat.evans@valtech.co.uk>
 */
class MarkerPluginManagerFactory extends AbstractPluginManagerFactory
{
    const CONFIG_KEY = 'marker_plugins';

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): MarkerPluginManager
    {
        return new MarkerPluginManager($container);
    }
}
