<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\WithdrawController;

class WithdrawControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return WithdrawController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): WithdrawController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new WithdrawController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return WithdrawController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): WithdrawController
    {
        return $this->__invoke($serviceLocator, WithdrawController::class);
    }
}