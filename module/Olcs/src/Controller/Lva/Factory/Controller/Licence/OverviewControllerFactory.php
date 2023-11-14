<?php

namespace Olcs\Controller\Lva\Factory\Controller\Licence;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Olcs\Controller\Lva\Licence\OverviewController;
use Olcs\Service\Helper\LicenceOverviewHelperService;
use ZfcRbac\Service\AuthorizationService;

class OverviewControllerFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  $requestedName
     * @param  array|null         $options
     * @return OverviewController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): OverviewController
    {
        
        $niTextTranslationUtil = $container->get(NiTextTranslation::class);
        $authService = $container->get(AuthorizationService::class);
        $applicationOverviewHelper = $container->get(LicenceOverviewHelperService::class);
        $formHelper = $container->get(FormHelperService::class);
        $navigation = $container->get('Navigation');
        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);

        return new OverviewController(
            $niTextTranslationUtil,
            $authService,
            $applicationOverviewHelper,
            $formHelper,
            $navigation,
            $flashMessengerHelper
        );
    }
}
