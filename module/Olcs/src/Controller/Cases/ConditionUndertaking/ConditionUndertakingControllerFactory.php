<?php

namespace Olcs\Controller\Cases\ConditionUndertaking;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\HelperPluginManager;

class ConditionUndertakingControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ConditionUndertakingController
    {
        $translationHelper = $container->get(TranslationHelperService::class);
        assert($translationHelper instanceof TranslationHelperService);

        $formHelper = $container->get(FormHelperService::class);
        assert($formHelper instanceof FormHelperService);

        $flashMessenger = $container->get(FlashMessengerHelperService::class);
        assert($flashMessenger instanceof FlashMessengerHelperService);

        $navigation = $container->get('navigation');
        assert($navigation instanceof Navigation);

        $viewHelperManager = $container->get('ViewHelperManager');
        assert($viewHelperManager instanceof HelperPluginManager);
        
        return new ConditionUndertakingController(
            $translationHelper,
            $formHelper,
            $flashMessenger,
            $navigation,
            $viewHelperManager);
    }
    public function createService(ServiceLocatorInterface $serviceLocator): ConditionUndertakingController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;

        return $this->__invoke($container,
            ConditionUndertakingController::class);
    }
}
