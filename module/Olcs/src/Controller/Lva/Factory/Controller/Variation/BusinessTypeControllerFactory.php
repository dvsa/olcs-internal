<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\BusinessTypeController;

class BusinessTypeControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return BusinessTypeController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): BusinessTypeController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new BusinessTypeController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return BusinessTypeController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): BusinessTypeController
    {
        return $this->__invoke($serviceLocator, BusinessTypeController::class);
    }
}