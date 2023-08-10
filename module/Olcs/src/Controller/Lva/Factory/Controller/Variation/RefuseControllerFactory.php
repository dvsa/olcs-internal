<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\RefuseController;

class RefuseControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return RefuseController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): RefuseController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new RefuseController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RefuseController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): RefuseController
    {
        return $this->__invoke($serviceLocator, RefuseController::class);
    }
}