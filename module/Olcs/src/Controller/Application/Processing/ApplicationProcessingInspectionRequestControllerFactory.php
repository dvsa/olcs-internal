<?php

namespace Olcs\Controller\Application\Processing;
use Common\Service\Cqrs\Query\QueryService;
use Common\Service\Helper\FlashMessengerHelperService;
use Dvsa\Olcs\Transfer\Util\Annotation\AnnotationBuilder as TransferAnnotationBuilder;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Service\Data\OperatingCentresForInspectionRequest;

class ApplicationProcessingInspectionRequestControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ApplicationProcessingInspectionRequestController
{
    if (method_exists($container, 'getServiceLocator') && $container->getServiceLocator()) {
        $container = $container->getServiceLocator();
    }
    $transferAnnotationBuilder = $container->get(TransferAnnotationBuilder::class);
    $queryService = $container->get(QueryService::class);
    $flashMessengerHelper = $container->get(FlashMessengerHelperService::class);
    $operatingCentresForInspectionRequest = $container->get(OperatingCentresForInspectionRequest::class);
    return new ApplicationProcessingInspectionRequestController(
         $transferAnnotationBuilder,
         $queryService,
        $flashMessengerHelper,
         $operatingCentresForInspectionRequest);
}

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @deprecated
     */
    public function createService(ServiceLocatorInterface $serviceLocator): ApplicationProcessingInspectionRequestController
{
    return $this->__invoke($serviceLocator, ApplicationProcessingInspectionRequestController::class);
}
}