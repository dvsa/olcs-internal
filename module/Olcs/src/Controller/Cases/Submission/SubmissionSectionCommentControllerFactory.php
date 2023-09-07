<?php

namespace Olcs\Controller\Cases\Submission;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Helper\UrlHelperService;
use Common\View\Helper\Config;
use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\Renderer\PhpRenderer as ViewRenderer;

class SubmissionSectionCommentControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): SubmissionSectionCommentController
    {
        $translationHelper = $container->get(TranslationHelperService::class);
        assert($translationHelper instanceof TranslationHelperService);

        $formHelper = $container->get(FormHelperService::class);
        assert($formHelper instanceof FormHelperService);

        $flashMessenger = $container->get(FlashMessengerHelperService::class);
        assert($flashMessenger instanceof FlashMessengerHelperService);

        $navigation = $container->get('navigation');
        assert($navigation instanceof Navigation);

        return new SubmissionSectionCommentController(
            $translationHelper,
            $formHelper,
            $flashMessenger,
            $navigation);
    }
    public function createService(ServiceLocatorInterface $serviceLocator): SubmissionSectionCommentController
    {
        $container = method_exists($serviceLocator, 'getServiceLocator') ? $serviceLocator->getServiceLocator(): $serviceLocator;

        return $this->__invoke($container,
            SubmissionSectionCommentController::class);
    }
}
