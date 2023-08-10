<?php

namespace Olcs\Controller\Lva\Factory\Controller\Variation;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Controller\Lva\Variation\CommunityLicencesController;

class CommunityLicencesControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return CommunityLicencesController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): CommunityLicencesController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;
        //ToDo: Migrate SM calls here
        return new CommunityLicencesController();
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return CommunityLicencesController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): CommunityLicencesController
    {
        return $this->__invoke($serviceLocator, CommunityLicencesController::class);
    }
}