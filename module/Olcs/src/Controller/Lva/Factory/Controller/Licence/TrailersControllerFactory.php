<?php

namespace Olcs\Controller\Lva\Factory\Controller\Licence;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Licence\TrailersController;

class TrailersControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return TrailersController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): TrailersController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new TrailersController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return TrailersController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): TrailersController
    {
        return $this->__invoke($serviceLocator, TrailersController::class);
    }
}