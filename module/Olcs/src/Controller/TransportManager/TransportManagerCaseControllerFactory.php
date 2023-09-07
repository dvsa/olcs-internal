<?php

namespace Olcs\Controller\TransportManager;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class TransportManagerCaseControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): TransportManagerCaseController
    {
        $translationHelper = $container->get(TranslationHelperService::class);
        assert($translationHelper instanceof TranslationHelperService);

        $formHelperService = $container->get(FormHelperService::class);
        assert($formHelperService instanceof FormHelperService);

        $flashMessenger = $container->get(FlashMessengerHelperService::class);
        assert($flashMessenger instanceof FlashMessengerHelperService);

        $navigation = $container->get('navigation');
        assert($navigation instanceof Navigation);

        return new TransportManagerCaseController(
            $translationHelper,
            $formHelperService,
            $flashMessenger,
            $navigation);
    }
    public function createService(ServiceLocatorInterface $serviceLocator): TransportManagerCaseController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;

        return $this->__invoke($container,
            TransportManagerCaseController::class);
    }
}
