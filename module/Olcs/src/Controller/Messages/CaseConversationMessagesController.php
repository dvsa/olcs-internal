<?php

declare(strict_types=1);

namespace Olcs\Controller\Messages;

use Olcs\Controller\Interfaces\CaseControllerInterface;

class CaseConversationMessagesController extends AbstractConversationMessagesController implements CaseControllerInterface
{
    protected $navigationId = 'case_conversations';
    protected $topNavigationId = 'licence';
    protected $listVars = ['licence', 'conversation'];
}
