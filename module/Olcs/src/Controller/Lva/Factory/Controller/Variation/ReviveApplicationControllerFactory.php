<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\ReviveApplicationController;

class ReviveApplicationControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return ReviveApplicationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ReviveApplicationController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new ReviveApplicationController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ReviveApplicationController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): ReviveApplicationController
    {
        return $this->__invoke($serviceLocator, ReviveApplicationController::class);
    }
}