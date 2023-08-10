<?php

namespace Olcs\Controller\Lva\Factory\Controller\Application;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Application\GrantController;

class GrantControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return GrantController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): GrantController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new GrantController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return GrantController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): GrantController
    {
        return $this->__invoke($serviceLocator, GrantController::class);
    }
}