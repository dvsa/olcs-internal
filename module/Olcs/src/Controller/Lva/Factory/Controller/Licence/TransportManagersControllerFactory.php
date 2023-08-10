<?php

namespace Olcs\Controller\Lva\Factory\Controller\Licence;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Licence\TransportManagersController;

class TransportManagersControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return TransportManagersController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): TransportManagersController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new TransportManagersController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return TransportManagersController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): TransportManagersController
    {
        return $this->__invoke($serviceLocator, TransportManagersController::class);
    }
}