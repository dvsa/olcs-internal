<?php

namespace Olcs\Controller\Factory\Bus\Processing;

use Common\Service\Helper\FormHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Interop\Container\ContainerInterface;
use Laminas\Mvc\Router\Http\TreeRouteStack;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\HelperPluginManager;
use Olcs\Controller\Bus\Processing\BusProcessingTaskController;
use Olcs\Service\Data\SubCategory;

class BusProcessingTaskControllerFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  $requestedName
     * @param  array|null         $options
     * @return BusProcessingTaskController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): BusProcessingTaskController
    {
        $scriptFactory = $container->get(ScriptFactory::class);
        $formHelper = $container->get(FormHelperService::class);
        $tableFactory = $container->get(TableFactory::class);
        $viewHelperManager = $container->get(HelperPluginManager::class);
        $router = $container->get(TreeRouteStack::class);
        $subCategoryDataService = $container->get(SubCategory::class);

        return new BusProcessingTaskController(
            $scriptFactory,
            $formHelper,
            $tableFactory,
            $viewHelperManager,
            $router,
            $subCategoryDataService
        );
    }
}
