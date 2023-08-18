<?php

namespace Olcs\Controller\Application;

use Common\Service\Helper\FlashMessengerHelperService;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Common\Service\Data\Application as ApplicationData;

class ApplicationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ApplicationController
    {
        $applicationData = $container->get(ApplicationData::class);
        assert($applicationData instanceof ApplicationData);

        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
        assert($flashMessengerHelper instanceof FlashMessengerHelperService);

    return new ApplicationController(
        $applicationData,
        $flashMessengerHelper
    );
    }

    public function createService(ServiceLocatorInterface $serviceLocator): ApplicationController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;
        return $this->__invoke($container,
            ApplicationController::class);
    }
}
