<?php

namespace Admin\Controller;

use Common\Service\AntiVirus\Scan;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Interop\Container\ContainerInterface;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Service\Data\SubCategory;

class DocumentTemplateControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): DocumentTemplateController
    {
        $translationHelper = $container->get(TranslationHelperService::class);
        assert($translationHelper instanceof TranslationHelperService);

        $formHelperService = $container->get(FormHelperService::class);
        assert($formHelperService instanceof FormHelperService);

        $flashMessenger = $container->get(FlashMessengerHelperService::class);
        assert($flashMessenger instanceof FlashMessengerHelperService);

        $navigation = $container->get('navigation');
        assert($navigation instanceof Navigation);

        $scannerAntiVirusService = $container->get(Scan::class);
        assert($scannerAntiVirusService instanceof Scan);

        $subCategoryDataService = $container->get(SubCategory::class);
        assert($subCategoryDataService instanceof SubCategory);

        return new DocumentTemplateController(
            $translationHelper,
            $formHelperService,
            $flashMessenger,
            $navigation,
            $scannerAntiVirusService,
            $subCategoryDataService
        );
    }
    public function createService(ServiceLocatorInterface $serviceLocator): DocumentTemplateController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator() : $serviceLocator;

        return $this->__invoke(
            $container,
            DocumentTemplateController::class
        );
    }
}
