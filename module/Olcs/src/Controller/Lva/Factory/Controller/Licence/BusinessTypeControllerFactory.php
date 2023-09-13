<?php

namespace Olcs\Controller\Lva\Factory\Controller\Licence;

use Common\Controller\Lva\Adapters\GenericBusinessTypeAdapter;
use Common\FormService\FormServiceManager;
use Common\Service\Cqrs\Query\QueryService;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\StringHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Script\ScriptFactory;
use Dvsa\Olcs\Transfer\Util\Annotation\AnnotationBuilder;
use Dvsa\Olcs\Utils\Translation\NiTextTranslation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Licence\BusinessTypeController;
use ZfcRbac\Identity\IdentityProviderInterface;
use ZfcRbac\Service\AuthorizationService;

class BusinessTypeControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return BusinessTypeController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): BusinessTypeController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;

        $niTextTranslationUtil = $container->get(NiTextTranslation::class);
        $authService = $container->get(AuthorizationService::class);
        $formHelper = $container->get(FormHelperService::class);
        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
        $formServiceManager = $container->get(FormServiceManager::class);
        $scriptFactory = $container->get(ScriptFactory::class);
        $identityProvider = $container->get(IdentityProviderInterface::class);
        $queryService = $container->get(QueryService::class);
        $transferAnnotationBuilder = $container->get(AnnotationBuilder::class);
        $translationHelper = $container->get(TranslationHelperService::class);
        $lvaAdapter = $container->get(GenericBusinessTypeAdapter::class);

        return new BusinessTypeController(
            $niTextTranslationUtil,
            $authService,
            $formHelper,
            $flashMessengerHelper,
            $formServiceManager,
            $scriptFactory,
            $identityProvider,
            $translationHelper,
            $transferAnnotationBuilder,
            $queryService,
            $lvaAdapter
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return BusinessTypeController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): BusinessTypeController
    {
        return $this->__invoke($serviceLocator, BusinessTypeController::class);
    }
}
