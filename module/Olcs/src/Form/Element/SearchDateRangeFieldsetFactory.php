<?php

namespace Olcs\Form\Element;

use Interop\Container\ContainerInterface;
use Common\Service\Data\Search\Search;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Class SearchDateRangeFieldsetFactory
 *
 * @package Olcs\Form\Element
 */
class SearchDateRangeFieldsetFactory implements FactoryInterface
{
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator): SearchDateRangeFieldset
    {
        return $this->__invoke($serviceLocator, SearchDateRangeFieldset::class);
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return SearchDateRangeFieldset
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): SearchDateRangeFieldset
    {
        if (method_exists($container, 'getServiceLocator') && $container->getServiceLocator()) {
            $container = $container->getServiceLocator();
        }
        $fs = new SearchDateRangeFieldset($this->options['name'], $this->options);
        $fs->setSearchService($container->get('DataServiceManager')->get(Search::class));
        return $fs;
    }
}
