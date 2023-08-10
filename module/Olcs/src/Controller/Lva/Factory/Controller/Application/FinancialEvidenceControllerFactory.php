<?php

namespace Olcs\Controller\Lva\Factory\Controller\Application;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Application\FinancialEvidenceController;

class FinancialEvidenceControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return FinancialEvidenceController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): FinancialEvidenceController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new FinancialEvidenceController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return FinancialEvidenceController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): FinancialEvidenceController
    {
        return $this->__invoke($serviceLocator, FinancialEvidenceController::class);
    }
}