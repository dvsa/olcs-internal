<?php

namespace Olcs\Controller\Lva\Factory\Controller\Application;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Application\SafetyController;

class SafetyControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return SafetyController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): SafetyController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new SafetyController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return SafetyController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): SafetyController
    {
        return $this->__invoke($serviceLocator, SafetyController::class);
    }
}