<?php

namespace Olcs\Controller\Factory\Operator\Cases;

use Common\Service\Cqrs\Command\CommandService;
use Common\Service\Cqrs\Query\QueryService;
use Common\Service\Helper\DateHelperService;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Dvsa\Olcs\Transfer\Util\Annotation\AnnotationBuilder;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\HelperPluginManager;
use Olcs\Controller\Operator\Cases\UnlicensedCasesOperatorController;
use Olcs\Service\Data\Licence;

class UnlicensedCasesOperatorControllerFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  $requestedName
     * @param  array|null         $options
     * @return UnlicensedCasesOperatorController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): UnlicensedCasesOperatorController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;

        $scriptFactory = $container->get(ScriptFactory::class);
        $formHelper = $container->get(FormHelperService::class);
        $tableFactory = $container->get(TableFactory::class);
        $viewHelperManager = $container->get(HelperPluginManager::class);
        $dateHelper = $container->get(DateHelperService::class);
        $transferAnnotationBuilder = $container->get(AnnotationBuilder::class);
        $commandService = $container->get(CommandService::class);
        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
        $licenceDataService = $container->get(Licence::class);
        $queryService = $container->get(QueryService::class);
        $navigation = $container->get('navigation');

        return new UnlicensedCasesOperatorController(
            $scriptFactory,
            $formHelper,
            $tableFactory,
            $viewHelperManager,
            $dateHelper,
            $transferAnnotationBuilder,
            $commandService,
            $flashMessengerHelper,
            $licenceDataService,
            $queryService,
            $navigation,
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return UnlicensedCasesOperatorController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): UnlicensedCasesOperatorController
    {
        return $this->__invoke($serviceLocator, UnlicensedCasesOperatorController::class);
    }
}
