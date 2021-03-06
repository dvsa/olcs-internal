<?php

namespace Olcs\Service\Permits\Bilateral;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class StandardFieldsetPopulatorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return StandardFieldsetPopulator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new StandardFieldsetPopulator(
            $serviceLocator->get(NoOfPermitsElementGenerator::class)
        );
    }
}
