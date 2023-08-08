<?php

namespace Olcs\Controller\Factory\TransportManager\Details;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Helper\TransportManagerHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\HelperPluginManager;
use Olcs\Controller\TransportManager\Details\TransportManagerDetailsPreviousHistoryController;

class TransportManagerDetailsPreviousHistoryControllerFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  $requestedName
     * @param  array|null         $options
     * @return TransportManagerDetailsPreviousHistoryController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): TransportManagerDetailsPreviousHistoryController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;

        $scriptFactory = $container->get(ScriptFactory::class);
        $formHelper = $container->get(FormHelperService::class);
        $tableFactory = $container->get(TableFactory::class);
        $viewHelperManager = $container->get(HelperPluginManager::class);
        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
        $translationHelper = $container->get(TranslationHelperService::class);
        $navigation = $container->get('navigation');
        $transportManagerHelper = $container->get(TransportManagerHelperService::class);

        return new TransportManagerDetailsPreviousHistoryController(
            $scriptFactory,
            $formHelper,
            $tableFactory,
            $viewHelperManager,
            $flashMessengerHelper,
            $translationHelper,
            $navigation,
            $transportManagerHelper,
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return TransportManagerDetailsPreviousHistoryController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): TransportManagerDetailsPreviousHistoryController
    {
        return $this->__invoke($serviceLocator, TransportManagerDetailsPreviousHistoryController::class);
    }
}
