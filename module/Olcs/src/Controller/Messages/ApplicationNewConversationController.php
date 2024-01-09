<?php

namespace Olcs\Controller\Messages;

// use \Olcs\Controller\Messages\AbstractNewConversationController;
use Olcs\Controller\Interfaces\LeftViewProvider;

class ApplicationNewConversationController extends AbstractNewConversationController implements LeftViewProvider
{
    protected $navigationId = 'application_new_conversation';
}
