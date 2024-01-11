<?php

declare(strict_types=1);

namespace Olcs\Controller\Messages;

use Laminas\View\Model\ViewModel;
use Olcs\Controller\AbstractController;

class LicenceCloseConversationController extends AbstractController
{
    public function confirmAction(): ViewModel
    {
        $form = $this->getForm('CloseConversation');

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('pages/form');

        return $this->renderView($view, 'End Conversation', 'abc');
    }
}
