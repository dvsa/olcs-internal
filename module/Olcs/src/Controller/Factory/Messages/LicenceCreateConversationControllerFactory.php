<?php

namespace Olcs\Controller\Factory\Messages;

use Common\Service\Helper\FlashMessengerHelperService;
use Common\Service\Helper\FormHelperService;
use Common\Service\Helper\TranslationHelperService;
<<<<<<<< HEAD:module/Olcs/src/Controller/Factory/Messages/LicenceCreateConversationControllerFactory.php
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Olcs\Controller\Messages\LicenceCreateConversationController;
use Olcs\Service\Data\MessagingSubject;

class LicenceCreateConversationControllerFactory implements FactoryInterface
========
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Olcs\Controller\Messages\ApplicationCreateConversationController;

class ApplicationCreateConversationControllerFactory implements FactoryInterface
>>>>>>>> main:module/Olcs/src/Controller/Factory/Messages/ApplicationCreateConversationControllerFactory.php
{
    /**
     * @param  ContainerInterface $container
     * @param  $requestedName
     * @param  array|null         $options
<<<<<<<< HEAD:module/Olcs/src/Controller/Factory/Messages/LicenceCreateConversationControllerFactory.php
     * @return LicenceCreateConversationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): LicenceCreateConversationController
========
     * @return ApplicationCreateConversationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ApplicationCreateConversationController
>>>>>>>> main:module/Olcs/src/Controller/Factory/Messages/ApplicationCreateConversationControllerFactory.php
    {
        $container = method_exists($container, 'getServiceLocator') ? $container->getServiceLocator() : $container;

        $formHelper = $container->get(FormHelperService::class);

        $translationHelper = $container->get(TranslationHelperService::class);

        $flashMessenger = $container->get(FlashMessengerHelperService::class);

        $navigation = $container->get('navigation');

<<<<<<<< HEAD:module/Olcs/src/Controller/Factory/Messages/LicenceCreateConversationControllerFactory.php
        return new LicenceCreateConversationController(
========
        return new ApplicationCreateConversationController(
>>>>>>>> main:module/Olcs/src/Controller/Factory/Messages/ApplicationCreateConversationControllerFactory.php
            $translationHelper,
            $formHelper,
            $flashMessenger,
            $navigation
        );
    }
}
