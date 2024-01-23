<?php

namespace Olcs\Controller\Messages;

use Olcs\Controller\Interfaces\LeftViewProvider;

class LicenceCreateConversationController extends AbstractCreateConversationController implements LeftViewProvider
{
    protected $navigationId = 'licence_new_conversation';
}
