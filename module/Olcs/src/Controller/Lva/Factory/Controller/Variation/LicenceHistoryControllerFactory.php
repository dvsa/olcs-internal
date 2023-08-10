<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\LicenceHistoryController;

class LicenceHistoryControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return LicenceHistoryController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): LicenceHistoryController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new LicenceHistoryController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return LicenceHistoryController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): LicenceHistoryController
    {
        return $this->__invoke($serviceLocator, LicenceHistoryController::class);
    }
}