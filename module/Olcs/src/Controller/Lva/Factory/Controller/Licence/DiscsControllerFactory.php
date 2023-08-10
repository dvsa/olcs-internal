<?php

namespace Olcs\Controller\Lva\Factory\Controller\Licence;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Licence\DiscsController;

class DiscsControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return DiscsController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): DiscsController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new DiscsController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return DiscsController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): DiscsController
    {
        return $this->__invoke($serviceLocator, DiscsController::class);
    }
}