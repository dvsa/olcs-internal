<?php

namespace Admin\Controller\DataRetention;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\ResponseHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Table\TableBuilder;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ExportControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ExportController
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

        $responseHelperService = $container->get(ResponseHelperService::class);
        assert($responseHelperService instanceof ResponseHelperService);

        return new ExportController(
            $translationHelper,
            $formHelperService,
            $flashMessenger,
            $navigation,
            $tableBuilder,
            $responseHelperService);
    }
    public function createService(ServiceLocatorInterface $serviceLocator): ExportController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;

        return $this->__invoke($container,
            ExportController::class);
    }
}
