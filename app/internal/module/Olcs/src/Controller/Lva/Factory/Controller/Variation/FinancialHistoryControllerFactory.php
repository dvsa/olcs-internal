<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Common\FormService\FormServiceManager;
use Common\Service\Helper\DataHelperService;
use Common\Service\Helper\FileUploadHelperService;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\StringHelperService;
use Common\Service\Script\ScriptFactory;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\FinancialHistoryController;
use ZfcRbac\Service\AuthorizationService;

class FinancialHistoryControllerFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  $requestedName
     * @param  array|null         $options
     * @return FinancialHistoryController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): FinancialHistoryController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;

        $niTextTranslationUtil = $container->get(NiTextTranslation::class);
        $authService = $container->get(AuthorizationService::class);
        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
        $formServiceManager = $container->get(FormServiceManager::class);
        $scriptFactory = $container->get(ScriptFactory::class);
        $dataHelper = $container->get(DataHelperService::class);
        $fileUploadHelper = $container->get(FileUploadHelperService::class);
        $stringHelper = $container->get(StringHelperService::class);
        $navigation = $container->get('Navigation');

        return new FinancialHistoryController(
            $niTextTranslationUtil,
            $authService,
            $flashMessengerHelper,
            $formServiceManager,
            $scriptFactory,
            $dataHelper,
            $fileUploadHelper,
            $stringHelper,
            $navigation
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return FinancialHistoryController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): FinancialHistoryController
    {
        return $this->__invoke($serviceLocator, FinancialHistoryController::class);
    }
}