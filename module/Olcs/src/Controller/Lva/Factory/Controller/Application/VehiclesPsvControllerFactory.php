<?php

namespace Olcs\Controller\Lva\Factory\Controller\Application;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Application\VehiclesPsvController;

class VehiclesPsvControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return VehiclesPsvController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): VehiclesPsvController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new VehiclesPsvController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return VehiclesPsvController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): VehiclesPsvController
    {
        return $this->__invoke($serviceLocator, VehiclesPsvController::class);
    }
}