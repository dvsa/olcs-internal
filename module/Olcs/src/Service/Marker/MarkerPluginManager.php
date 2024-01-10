<?php

namespace Olcs\Service\Marker;

use Laminas\ServiceManager\AbstractPluginManager;
use Interop\Container\ContainerInterface;

/**
 * Class MarkerPluginManager
 */
class MarkerPluginManager extends AbstractPluginManager
{
    protected $instanceOf = MarkerInterface::class;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->addInitializer(new PartialHelperInitializer());
    }
}
