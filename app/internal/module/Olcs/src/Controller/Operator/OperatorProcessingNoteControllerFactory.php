<?php

namespace Olcs\Controller\Operator;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Interop\Container\ContainerInterface;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class OperatorProcessingNoteControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): OperatorProcessingNoteController
    {
        $translationHelperService = $container->get(TranslationHelperService::class);
        assert($translationHelperService instanceof TranslationHelperService);

        $formHelper = $container->get(FormHelperService::class);
        assert($formHelper instanceof FormHelperService);

        $flashMessengerHelperService = $container->get(FlashMessengerHelperService::class);
        assert($flashMessengerHelperService instanceof FlashMessengerHelperService);

        $navigation = $container->get('navigation');
        assert($navigation instanceof Navigation);

        return new OperatorProcessingNoteController(
            $translationHelperService,
            $formHelper,
            $flashMessengerHelperService,
            $navigation
        );
    }
    public function createService(ServiceLocatorInterface $serviceLocator): OperatorProcessingNoteController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator() : $serviceLocator;

        return $this->__invoke(
            $container,
            OperatorProcessingNoteController::class
        );
    }
}
