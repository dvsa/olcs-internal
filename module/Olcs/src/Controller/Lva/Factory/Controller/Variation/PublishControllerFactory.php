<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\PublishController;

class PublishControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return PublishController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PublishController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new PublishController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return PublishController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): PublishController
    {
        return $this->__invoke($serviceLocator, PublishController::class);
    }
}