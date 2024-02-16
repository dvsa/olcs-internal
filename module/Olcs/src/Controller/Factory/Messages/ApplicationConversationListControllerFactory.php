<?php

namespace Olcs\Controller\Factory\Messages;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Olcs\Controller\Messages\ApplicationConversationListController;

class ApplicationConversationListControllerFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  $requestedName
     * @param  array|null         $options
     * @return ApplicationConversationListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ApplicationConversationListController
    {
        $formHelper = $container->get(FormHelperService::class);

        $translationHelper = $container->get(TranslationHelperService::class);

        $flashMessenger = $container->get(FlashMessengerHelperService::class);

        $navigation = $container->get('navigation');

        return new ApplicationConversationListController(
            $translationHelper,
            $formHelper,
            $flashMessenger,
            $navigation
        );
    }
}
