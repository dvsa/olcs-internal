<?php

declare(strict_types=1);

namespace Olcs\Controller\Factory\Messages;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Laminas\Navigation\Navigation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\HelperPluginManager;
use Olcs\Controller\Messages\LicenceCloseConversationController;
use Olcs\Controller\Messages\LicenceNewConversationController;
use Olcs\Controller\TaskController;
use Olcs\Service\Data\SubCategory;
use Olcs\Service\Data\UserListInternalExcludingLimitedReadOnlyUsers;

class LicenceCloseConversationControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): LicenceCloseConversationController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;

        $scriptFactory = $container->get(ScriptFactory::class);
        $formHelper = $container->get(FormHelperService::class);
        $tableFactory = $container->get(TableFactory::class);
        $viewHelperManager = $container->get(HelperPluginManager::class);

        return new LicenceCloseConversationController(
            $scriptFactory,
            $formHelper,
            $tableFactory,
            $viewHelperManager,
        );
    }

    public function createService(ServiceLocatorInterface $serviceLocator): LicenceCloseConversationController
    {
        return $this->__invoke($serviceLocator, LicenceCloseConversationController::class);
    }
}
