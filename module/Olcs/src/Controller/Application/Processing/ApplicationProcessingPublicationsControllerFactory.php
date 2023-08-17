<?php

namespace Olcs\Controller\Application\Processing;

use Common\Service\Helper\FlashMessengerHelperService;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ApplicationProcessingPublicationsControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ApplicationProcessingPublicationsController
    {
        if (method_exists($container, 'getServiceLocator') && $container->getServiceLocator()) {
        $container = $container->getServiceLocator();
    }

    $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);

    return new ApplicationProcessingPublicationsController(
        $flashMessengerHelper);
    }

    public function createService(ServiceLocatorInterface $serviceLocator): ApplicationProcessingPublicationsController
    {
        return $this->__invoke($serviceLocator,
            ApplicationProcessingInspectionRequestController::class);
    }
}
