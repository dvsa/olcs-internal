<?php

namespace Olcs\Controller\Factory\Application;

use Common\Service\Cqrs\Command\CommandService;
use Common\Service\Cqrs\Query\QueryService;
use Common\Service\Data\PluginManager;
use Common\Service\Helper\ComplaintsHelperService;
use Common\Service\Helper\DateHelperService;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\OppositionHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Dvsa\Olcs\Transfer\Util\Annotation\AnnotationBuilder;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\HelperPluginManager;
use Olcs\Controller\Application\ApplicationController;
use Olcs\Controller\IndexController;
use Olcs\Controller\Operator\OperatorController;
use Olcs\Service\Data\Licence;

class ApplicationControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return ApplicationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ApplicationController
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

        return new ApplicationController(
            $scriptFactory,
            $formHelper,
            $tableFactory,
            $viewHelperManager,
            $dataServiceManager,
            $oppositionHelper,
            $complaintsHelper,
            $flashMessengerHelper
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ApplicationController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): ApplicationController
    {
        return $this->__invoke($serviceLocator, ApplicationController::class);
    }
}
