<?php

namespace Olcs\Controller\Bus\Processing;

use Common\Service\Helper\FlashMessengerHelperService;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Application\Processing\ApplicationProcessingPublicationsController;

class BusProcessingDecisionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): BusProcessingDecisionController
    {
        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
        assert($flashMessengerHelper instanceof FlashMessengerHelperService);

    return new BusProcessingDecisionController(
        $flashMessengerHelper);
    }

    public function createService(ServiceLocatorInterface $serviceLocator): BusProcessingDecisionController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;

        return $this->__invoke($container,
            BusProcessingDecisionController::class);
    }
}
