<?php

namespace Olcs\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Class SlaIndicatorFactory
 *
 * @package Olcs\View\Helper
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class SlaIndicatorFactory implements FactoryInterface
{
    /**
     * Create SlaIndicator with injected table builder
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return SubmissionSectionTable
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SlaIndicator();
    }
}
