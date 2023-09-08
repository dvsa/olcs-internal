<?php

namespace Olcs\Controller\Licence\Processing;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Service\Data\OperatingCentresForInspectionRequest;
use Dvsa\Olcs\Transfer\Util\Annotation\AnnotationBuilder;

class LicenceProcessingNoteControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): LicenceProcessingNoteController
    {
        $translationHelper = $container->get(TranslationHelperService::class);
        assert($translationHelper instanceof TranslationHelperService);

        $formHelper = $container->get(FormHelperService::class);
        assert($formHelper instanceof FormHelperService);

        $flashMessenger = $container->get(FlashMessengerHelperService::class);
        assert($flashMessenger instanceof FlashMessengerHelperService);

        $navigation = $container->get('navigation');
        assert($navigation instanceof Navigation);

        $setUpOcListboxService = $container->get(OperatingCentresForInspectionRequest::class);
        assert($setUpOcListboxService instanceof OperatingCentresForInspectionRequest);

        $annotationBuilderService = $container->get(AnnotationBuilder::class);
        assert($annotationBuilderService instanceof AnnotationBuilder);

        return new LicenceProcessingNoteController(
            $translationHelper,
            $formHelper,
            $flashMessenger,
            $navigation,
            $setUpOcListboxService,
            $annotationBuilderService);
    }
    public function createService(ServiceLocatorInterface $serviceLocator): LicenceProcessingNoteController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;

        return $this->__invoke($container,
            LicenceProcessingNoteController::class);
    }
}
