<?php

declare(strict_types=1);

namespace Olcs\Controller\Factory\Messages;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
use Common\Service\Script\ScriptFactory;
use Olcs\Controller\Messages\CaseConversationMessagesController;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CaseConversationMessagesControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): CaseConversationMessagesController
    {
        $formHelper = $container->get(FormHelperService::class);
        $translationHelper = $container->get(TranslationHelperService::class);
        $flashMessenger = $container->get(FlashMessengerHelperService::class);
        $scriptsFactory = $container->get(ScriptFactory::class);

        $navigation = $container->get('navigation');

        return new CaseConversationMessagesController(
            $translationHelper,
            $formHelper,
            $flashMessenger,
            $navigation,
            $scriptsFactory
        );
    }
}
