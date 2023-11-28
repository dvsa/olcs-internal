<?php

namespace Admin\Controller;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Script\ScriptFactory;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Service\Data\ScannerSubCategory;
use Olcs\Service\Data\SubCategoryDescription;

class ScanningControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ScanningController
    {
        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
        $formHelper = $container->get(FormHelperService::class);
        $scannerSubCategoryDataService = $container->get(ScannerSubCategory::class);
        $subCategoryDescriptionDataService = $container->get(SubCategoryDescription::class);
        $scriptFactory = $container->get(ScriptFactory::class);

        return new ScanningController(
            $flashMessengerHelper,
            $formHelper,
            $scannerSubCategoryDataService,
            $subCategoryDescriptionDataService,
            $scriptFactory
        );
    }
    public function createService(ServiceLocatorInterface $serviceLocator): ScanningController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator() : $serviceLocator;

        return $this->__invoke($container, ScanningController::class);
    }
}