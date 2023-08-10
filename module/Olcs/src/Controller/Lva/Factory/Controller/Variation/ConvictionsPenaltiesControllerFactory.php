<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\ConvictionsPenaltiesController;

class ConvictionsPenaltiesControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return ConvictionsPenaltiesController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ConvictionsPenaltiesController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new ConvictionsPenaltiesController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ConvictionsPenaltiesController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): ConvictionsPenaltiesController
    {
        return $this->__invoke($serviceLocator, ConvictionsPenaltiesController::class);
    }
}