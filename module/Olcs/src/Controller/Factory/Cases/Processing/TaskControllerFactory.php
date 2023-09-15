<?php

namespace Olcs\Controller\Factory\Cases\Processing;

use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Interop\Container\ContainerInterface;
use Laminas\Router\Http\TreeRouteStack;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\HelperPluginManager;
use Olcs\Controller\Cases\Docs\CaseDocsController;
use Olcs\Controller\Cases\Processing\TaskController;
use Olcs\Service\Data\DocumentSubCategory;

class TaskControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return TaskController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): TaskController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;

        $scriptFactory = $container->get(ScriptFactory::class);
        $formHelper = $container->get(FormHelperService::class);
        $tableFactory = $container->get(TableFactory::class);
        $viewHelperManager = $container->get(HelperPluginManager::class);
        $router = $container->get(TreeRouteStack::class);

        return new TaskController(
            $scriptFactory,
            $formHelper,
            $tableFactory,
            $viewHelperManager,
            $router,
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return TaskController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): TaskController
    {
        return $this->__invoke($serviceLocator, TaskController::class);
    }
}
