<?php

namespace Olcs\Controller\Lva\Factory\Controller\Licence;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Licence\VariationController;

class VariationControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return VariationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): VariationController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new VariationController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return VariationController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): VariationController
    {
        return $this->__invoke($serviceLocator, VariationController::class);
    }
}