<?php

namespace Olcs\Service\Marker;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Config;
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
        $config = $container->get('Config');
        $configObject = new Config(!empty($config[static::CONFIG_KEY]) ? $config[static::CONFIG_KEY] : null);

        return new MarkerPluginManager($configObject);
    }
}
