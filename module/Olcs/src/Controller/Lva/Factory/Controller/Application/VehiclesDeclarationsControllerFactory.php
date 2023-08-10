<?php

namespace Olcs\Controller\Lva\Factory\Controller\Application;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Application\VehiclesDeclarationsController;

class VehiclesDeclarationsControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return VehiclesDeclarationsController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): VehiclesDeclarationsController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new VehiclesDeclarationsController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return VehiclesDeclarationsController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): VehiclesDeclarationsController
    {
        return $this->__invoke($serviceLocator, VehiclesDeclarationsController::class);
    }
}