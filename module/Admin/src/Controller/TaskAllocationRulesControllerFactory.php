<?php

namespace Admin\Controller;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Table\TableBuilder;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Service\Data\UserListInternal;

class TaskAllocationRulesControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): TaskAllocationRulesController
    {
        $translationHelper = $container->get(TranslationHelperService::class);
        assert($translationHelper instanceof TranslationHelperService);

        $formHelperService = $container->get(FormHelperService::class);
        assert($formHelperService instanceof FormHelperService);

        $flashMessenger = $container->get(FlashMessengerHelperService::class);
        assert($flashMessenger instanceof FlashMessengerHelperService);

        $navigation = $container->get('navigation');
        assert($navigation instanceof Navigation);

        $tableBuilder = $container->get(TableBuilder::class);
        assert($tableBuilder instanceof TableBuilder);

        $userListInternal = $container->get(UserListInternal::class);
        assert($userListInternal instanceof UserListInternal);

        return new TaskAllocationRulesController(
            $translationHelper,
            $formHelperService,
            $flashMessenger,
            $navigation,
            $tableBuilder,
            $userListInternal);
    }
    public function createService(ServiceLocatorInterface $serviceLocator): TaskAllocationRulesController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;

        return $this->__invoke($container,
            TaskAllocationRulesController::class);
    }
}
