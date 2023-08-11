<?php

namespace Olcs\Controller\Lva\Factory\Controller\Application;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Application\TaxiPhvController;
use ZfcRbac\Service\AuthorizationService;

class TaxiPhvControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return TaxiPhvController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): TaxiPhvController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;

        $niTextTranslationUtil = $container->get(NiTextTranslation::class);
        $authService = $container->get(AuthorizationService::class);
        $formHelper = $container->get(FormHelperService::class);
        $formServiceManager = $container->get(FormServiceManager::class);
        $scriptFactory = $container->get(ScriptFactory::class);
        $variationLvaService = $container->get(VariationLvaService::class);
        $guidanceHelper = $container->get(GuidanceHelperService::class);
        $stringHelper = $container->get(StringHelperService::class);

        return new TaxiPhvController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return TaxiPhvController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): TaxiPhvController
    {
        return $this->__invoke($serviceLocator, TaxiPhvController::class);
    }
}
