<?php

namespace Olcs\Controller\Bus\Registration;

use Common\Service\Helper\FlashMessengerHelperService;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BusRegistrationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): BusRegistrationController
    {
        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
        assert($flashMessengerHelper instanceof FlashMessengerHelperService);

    return new BusRegistrationController(
        $flashMessengerHelper);
    }

    public function createService(ServiceLocatorInterface $serviceLocator): BusRegistrationController
    {
        return $this->__invoke($serviceLocator,
            BusRegistrationController::class);
    }
}
