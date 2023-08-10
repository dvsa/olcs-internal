<?php

namespace Olcs\Controller\Lva\Factory\Controller\Application;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Application\SubmitController;

class SubmitControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return SubmitController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): SubmitController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new SubmitController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return SubmitController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): SubmitController
    {
        return $this->__invoke($serviceLocator, SubmitController::class);
    }
}