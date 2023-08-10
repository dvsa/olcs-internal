<?php

namespace Olcs\Controller\Lva\Factory\Controller\Application;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Application\TaxiPhvController;

class TaxiPhvControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return TaxiPhvController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): TaxiPhvController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new TaxiPhvController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return TaxiPhvController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): TaxiPhvController
    {
        return $this->__invoke($serviceLocator, TaxiPhvController::class);
    }
}