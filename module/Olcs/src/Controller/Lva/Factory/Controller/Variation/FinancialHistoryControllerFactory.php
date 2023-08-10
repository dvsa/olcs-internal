<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\FinancialHistoryController;

class FinancialHistoryControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return FinancialHistoryController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): FinancialHistoryController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new FinancialHistoryController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return FinancialHistoryController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): FinancialHistoryController
    {
        return $this->__invoke($serviceLocator, FinancialHistoryController::class);
    }
}