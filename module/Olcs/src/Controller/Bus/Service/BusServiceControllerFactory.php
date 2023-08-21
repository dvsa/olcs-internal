<?php

namespace Olcs\Controller\Bus\Service;

use Common\Service\Helper\FormHelperService;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BusServiceControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): BusServiceController
    {
        $formHelper = $container->get(FormHelperService::class);
        assert($formHelper instanceof FormHelperService);

    return new BusServiceController(
        $formHelper);
    }

    public function createService(ServiceLocatorInterface $serviceLocator): BusServiceController
    {
        return $this->__invoke($serviceLocator,
            BusServiceController::class);
    }
}
