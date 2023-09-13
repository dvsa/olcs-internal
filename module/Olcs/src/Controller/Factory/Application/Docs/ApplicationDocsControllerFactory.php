<?php

namespace Olcs\Controller\Factory\Application\Docs;

use Common\Service\Data\PluginManager;
use Common\Service\Helper\ComplaintsHelperService;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\OppositionHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\HelperPluginManager;
use Olcs\Controller\Application\ApplicationController;
use Olcs\Controller\Application\Docs\ApplicationDocsController;
use Olcs\Service\Data\DocumentSubCategory;

class ApplicationDocsControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return ApplicationDocsController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ApplicationDocsController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;

        $scriptFactory = $container->get(ScriptFactory::class);
        $formHelper = $container->get(FormHelperService::class);
        $tableFactory = $container->get(TableFactory::class);
        $viewHelperManager = $container->get(HelperPluginManager::class);
        $dataServiceManager = $container->get(PluginManager::class);
        $oppositionHelper = $container->get(OppositionHelperService::class);
        $complaintsHelper = $container->get(ComplaintsHelperService::class);
        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
        $docSubCategoryDataService = $container->get(DocumentSubCategory::class);

        return new ApplicationDocsController(
            $scriptFactory,
            $formHelper,
            $tableFactory,
            $viewHelperManager,
            $dataServiceManager,
            $oppositionHelper,
            $complaintsHelper,
            $flashMessengerHelper,
            $docSubCategoryDataService
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ApplicationDocsController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): ApplicationDocsController
    {
        return $this->__invoke($serviceLocator, ApplicationDocsController::class);
    }
}
