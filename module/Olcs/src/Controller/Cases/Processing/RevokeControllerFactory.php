<?php

namespace Olcs\Controller\Cases\Processing;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Interop\Container\ContainerInterface;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class RevokeControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): RevokeController
    {
        $translationHelper = $container->get(TranslationHelperService::class);
        assert($translationHelper instanceof TranslationHelperService);

        $formHelper = $container->get(FormHelperService::class);
        assert($formHelper instanceof FormHelperService);

        $flashMessenger = $container->get(FlashMessengerHelperService::class);
        assert($flashMessenger instanceof FlashMessengerHelperService);

        $navigation = $container->get('navigation');
        assert($navigation instanceof Navigation);

        return new RevokeController(
            $translationHelper,
            $formHelper,
            $flashMessenger,
            $navigation
        );
    }
    public function createService(ServiceLocatorInterface $serviceLocator): RevokeController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator() : $serviceLocator;

        return $this->__invoke(
            $container,
            RevokeController::class
        );
    }
}