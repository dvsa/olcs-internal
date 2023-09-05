<?php

namespace Olcs\Controller\IrhpPermits;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Qa\FieldsetPopulator;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class IrhpApplicationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IrhpApplicationController
    {
        $formHelper = $container->get(FormHelperService::class);
        assert($formHelper instanceof FormHelperService);

        $translationHelper = $container->get(TranslationHelperService::class);
        assert($translationHelper instanceof TranslationHelperService);

        $flashMessenger = $container->get(FlashMessengerHelperService::class);
        assert($flashMessenger instanceof FlashMessengerHelperService);

        $navigation = $container->get('navigation');
        assert($navigation instanceof Navigation);

        $QaFieldsetPopulator = $container->get('QaFieldsetPopulator');
        assert($QaFieldsetPopulator instanceof FieldsetPopulator);
        
        return new IrhpApplicationController(
            $translationHelper,
            $formHelper,
            $flashMessenger,
            $navigation,
            $QaFieldsetPopulator);
    }
    public function createService(ServiceLocatorInterface $serviceLocator): IrhpApplicationController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;

        return $this->__invoke($container,
            IrhpApplicationController::class);
    }
}
