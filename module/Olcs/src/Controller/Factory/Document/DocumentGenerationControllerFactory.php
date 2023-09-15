<?php

namespace Olcs\Controller\Factory\Document;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Script\ScriptFactory;
use Common\Service\Table\TableFactory;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\HelperPluginManager;
use Olcs\Controller\Document\DocumentGenerationController;
use Olcs\Service\Data\DocumentSubCategoryWithDocs;

class DocumentGenerationControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return DocumentGenerationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): DocumentGenerationController
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;

        $scriptFactory = $container->get(ScriptFactory::class);
        $formHelper = $container->get(FormHelperService::class);
        $tableFactory = $container->get(TableFactory::class);
        $viewHelperManager = $container->get(HelperPluginManager::class);
        $config = $container->get('Config');
        $flashMessangerHelper = $container->get(FlashMessengerHelperService::class);
        $docSubcategoryWithDocsDataService = $container->get(DocumentSubCategoryWithDocs::class);

        return new DocumentGenerationController(
            $scriptFactory,
            $formHelper,
            $tableFactory,
            $viewHelperManager,
            $config,
            $flashMessangerHelper,
            $docSubcategoryWithDocsDataService
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return DocumentGenerationController
     */
    public function createService(ServiceLocatorInterface $serviceLocator): DocumentGenerationController
    {
        return $this->__invoke($serviceLocator, DocumentGenerationController::class);
    }
}
