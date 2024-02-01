<?php

namespace Olcs\Controller\Messages;

use Olcs\Controller\Interfaces\LeftViewProvider;

class ApplicationCreateConversationController extends AbstractCreateConversationController implements LeftViewProvider
{
    protected $navigationId = 'application_new_conversation';
}
