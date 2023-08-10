<?php

namespace Olcs\Controller\Lva\Factory\Controller\Application;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Application\NotTakenUpController;

class NotTakenUpControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return NotTakenUpController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): NotTakenUpController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new NotTakenUpController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return NotTakenUpController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): NotTakenUpController
    {
        return $this->__invoke($serviceLocator, NotTakenUpController::class);
    }
}