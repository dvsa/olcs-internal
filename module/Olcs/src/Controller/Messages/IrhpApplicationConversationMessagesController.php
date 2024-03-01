<?php

declare(strict_types=1);

namespace Olcs\Controller\Messages;

use Olcs\Controller\Interfaces\CaseControllerInterface;
use Olcs\Controller\Interfaces\IrhpApplicationControllerInterface;

class IrhpApplicationConversationMessagesController extends AbstractConversationMessagesController implements IrhpApplicationControllerInterface
{
    protected $navigationId = 'irhp_conversations';
    protected $topNavigationId = 'licence';
    protected $listVars = ['licence', 'conversation'];
}
