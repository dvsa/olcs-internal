<?php

namespace Olcs\Controller\Lva\Factory\Controller\Licence;

use Common\Controller\Lva\Adapters\LicencePeopleAdapter;
use Common\FormService\FormServiceManager;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\GuidanceHelperService;
use Common\Service\Lva\VariationLvaService;
use Common\Service\Script\ScriptFactory;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Licence\PeopleController;
use ZfcRbac\Service\AuthorizationService;

class PeopleControllerFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  $requestedName
     * @param  array|null         $options
     * @return PeopleController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PeopleController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;

        $niTextTranslationUtil = $container->get(NiTextTranslation::class);
        $authService = $container->get(AuthorizationService::class);
        $formHelper = $container->get(FormHelperService::class);
        $formServiceManager = $container->get(FormServiceManager::class);
        $scriptFactory = $container->get(ScriptFactory::class);
        $variationLvaService = $container->get(VariationLvaService::class);
        $guidanceHelper = $container->get(GuidanceHelperService::class);
        $lvaAdapter = $container->get(LicencePeopleAdapter::class);
        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
        $navigation = $container->get('Navigation');

        return new PeopleController(
            $niTextTranslationUtil,
            $authService,
            $formHelper,
            $formServiceManager,
            $scriptFactory,
            $variationLvaService,
            $guidanceHelper,
            $lvaAdapter,
            $flashMessengerHelper,
            $navigation
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return PeopleController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): PeopleController
    {
        return $this->__invoke($serviceLocator, PeopleController::class);
    }
}