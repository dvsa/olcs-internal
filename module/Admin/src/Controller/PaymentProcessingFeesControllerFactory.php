<?php

namespace Admin\Controller;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class PaymentProcessingFeesControllerFactory implements FactoryInterface
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PaymentProcessingFeesController
    {
        return new PaymentProcessingFeesController();
    }

    public function createService(ServiceLocatorInterface $serviceLocator): PaymentProcessingFeesController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator() : $serviceLocator;

        return $this->__invoke($container, PaymentProcessingFeesController::class);
    }
}