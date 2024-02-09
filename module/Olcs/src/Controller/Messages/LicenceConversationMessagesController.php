<?php

declare(strict_types=1);

namespace Olcs\Controller\Messages;

class LicenceConversationMessagesController extends AbstractConversationMessagesController
{
    protected $navigationId = 'conversations';
    protected $topNavigationId = 'licence';
    protected $listVars = ['licence', 'conversation'];
}
