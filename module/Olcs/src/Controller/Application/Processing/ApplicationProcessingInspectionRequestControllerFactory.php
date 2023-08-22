<?php

namespace Olcs\Controller\Application\Processing;

use Common\Service\Cqrs\Query\CachingQueryService;
use Common\Service\Helper\FlashMessengerHelperService;
use Dvsa\Olcs\Transfer\Util\Annotation\AnnotationBuilder as TransferAnnotationBuilder;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Service\Data\OperatingCentresForInspectionRequest;

class ApplicationProcessingInspectionRequestControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ApplicationProcessingInspectionRequestController
    {
        $transferAnnotationBuilder = $container->get(TransferAnnotationBuilder::class);
        assert($transferAnnotationBuilder instanceof TransferAnnotationBuilder);

        $queryService = $container->get(CachingQueryService::class);
        assert($queryService instanceof CachingQueryService);

        $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
        $operatingCentresForInspectionRequest = $container->get(OperatingCentresForInspectionRequest::class);

        return new ApplicationProcessingInspectionRequestController(
            $transferAnnotationBuilder,
            $queryService,
            $flashMessengerHelper,
            $operatingCentresForInspectionRequest);
    }

    public function createService(ServiceLocatorInterface $serviceLocator): ApplicationProcessingInspectionRequestController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;

        return $this->__invoke($container,
            ApplicationProcessingInspectionRequestController::class);
    }
}
