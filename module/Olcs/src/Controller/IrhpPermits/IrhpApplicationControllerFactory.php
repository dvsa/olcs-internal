<?php

namespace Olcs\Controller\IrhpPermits;

use Common\Data\Mapper\Permits\NoOfPermits;
use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Qa\FieldsetPopulator;
use Interop\Container\ContainerInterface;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Data\Mapper\BilateralApplicationValidationModifier;
use Olcs\Data\Mapper\IrhpApplication;

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

        $bilateralApplicationValidationModifierMapper = $container->get(BilateralApplicationValidationModifier::class);
        $noOfPermitsMapper = $container->get(NoOfPermits::class);
        $irhpApplicationMapper = $container->get(IrhpApplication::class);

        return new IrhpApplicationController(
            $translationHelper,
            $formHelper,
            $flashMessenger,
            $navigation,
            $QaFieldsetPopulator,
            $bilateralApplicationValidationModifierMapper,
            $noOfPermitsMapper,
            $irhpApplicationMapper
        );
    }
    public function createService(ServiceLocatorInterface $serviceLocator): IrhpApplicationController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator() : $serviceLocator;

        return $this->__invoke(
            $container,
            IrhpApplicationController::class
        );
    }
}
