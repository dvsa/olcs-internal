<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\AddressesController;

class AddressesControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return AddressesController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AddressesController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new AddressesController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return AddressesController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): AddressesController
    {
        return $this->__invoke($serviceLocator, AddressesController::class);
    }
}