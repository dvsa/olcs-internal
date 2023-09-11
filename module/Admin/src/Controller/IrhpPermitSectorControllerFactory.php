<?php

namespace Admin\Controller;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Common\Service\Script\ScriptFactory;

class IrhpPermitSectorControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IrhpPermitSectorController
    {
        $translationHelper = $container->get(TranslationHelperService::class);
        assert($translationHelper instanceof TranslationHelperService);

        $formHelperService = $container->get(FormHelperService::class);
        assert($formHelperService instanceof FormHelperService);

        $flashMessenger = $container->get(FlashMessengerHelperService::class);
        assert($flashMessenger instanceof FlashMessengerHelperService);

        $navigation = $container->get('navigation');
        assert($navigation instanceof Navigation);

        $scriptFactory = $container->get(ScriptFactory::class);
        assert($scriptFactory instanceof ScriptFactory);

        return new IrhpPermitSectorController(
            $translationHelper,
            $formHelperService,
            $flashMessenger,
            $navigation,
            $scriptFactory);
    }
    public function createService(ServiceLocatorInterface $serviceLocator): IrhpPermitSectorController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;

        return $this->__invoke($container,
            IrhpPermitSectorController::class);
    }
}
