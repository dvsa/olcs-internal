<?php

namespace Olcs\Service\Data;

use Common\Service\Data\AbstractDataServiceServices;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * IrhpPermitPrintRangeTypeFactory
 */
class IrhpPermitPrintRangeTypeFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     *
     * @return IrhpPermitPrintRangeType
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IrhpPermitPrintRangeType
    {
        return new IrhpPermitPrintRangeType(
            $container->get(AbstractDataServiceServices::class),
            $container->get('Helper\Translation')
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $services
     *
     * @return IrhpPermitPrintRangeType
     */
    public function createService(ServiceLocatorInterface $services): IrhpPermitPrintRangeType
    {
        return $this($services, IrhpPermitPrintRangeType::class);
    }
}
