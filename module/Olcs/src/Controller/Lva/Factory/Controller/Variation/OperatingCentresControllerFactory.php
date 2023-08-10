<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\OperatingCentresController;

class OperatingCentresControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return OperatingCentresController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): OperatingCentresController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new OperatingCentresController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return OperatingCentresController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): OperatingCentresController
    {
        return $this->__invoke($serviceLocator, OperatingCentresController::class);
    }
}