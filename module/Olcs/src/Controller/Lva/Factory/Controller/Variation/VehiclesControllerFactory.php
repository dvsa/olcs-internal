<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\VehiclesController;

class VehiclesControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return VehiclesController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): VehiclesController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new VehiclesController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return VehiclesController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): VehiclesController
    {
        return $this->__invoke($serviceLocator, VehiclesController::class);
    }
}