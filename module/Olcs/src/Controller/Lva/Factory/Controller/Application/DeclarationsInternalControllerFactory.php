<?php

namespace Olcs\Controller\Lva\Factory\Controller\Application;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Application\DeclarationsInternalController;

class DeclarationsInternalControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return DeclarationsInternalController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): DeclarationsInternalController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new DeclarationsInternalController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return DeclarationsInternalController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): DeclarationsInternalController
    {
        return $this->__invoke($serviceLocator, DeclarationsInternalController::class);
    }
}