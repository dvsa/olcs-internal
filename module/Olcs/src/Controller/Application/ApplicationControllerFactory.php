<?php

namespace Olcs\Controller\Application;

use Common\Service\Data\PluginManager as DataServiceManager;
use \Common\Service\Helper\ComplaintsHelperService;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\OppositionHelperService;
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

        $oppositionHelperService = $container->get(OppositionHelperService::class);
        assert($oppositionHelperService instanceof OppositionHelperService);

        $complaintsHelperService = $container->get(ComplaintsHelperService::class);
        assert($complaintsHelperService instanceof ComplaintsHelperService);

        $formHelper = $container->get(FormHelperService::class);
        assert($formHelper instanceof FormHelperService);

        $dataServiceManager = $container->get(DataServiceManager::class);
        assert($dataServiceManager instanceof DataServiceManager);

    return new ApplicationController(
        $applicationData,
        $flashMessengerHelper,
        $oppositionHelperService,
        $complaintsHelperService,
        $formHelper,
        $dataServiceManager
    );
    }

    public function createService(ServiceLocatorInterface $serviceLocator): ApplicationController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;
        return $this->__invoke($container,
            ApplicationController::class);
    }
}
