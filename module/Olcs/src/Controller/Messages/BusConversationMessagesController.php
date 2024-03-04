<?php

declare(strict_types=1);

namespace Olcs\Controller\Messages;

use Olcs\Controller\Interfaces\BusRegControllerInterface;

class BusConversationMessagesController extends AbstractConversationMessagesController implements BusRegControllerInterface
{
    protected $navigationId = 'bus_conversations';
    protected $topNavigationId = 'licence';
    protected $listVars = ['licence', 'conversation'];

    protected function getConversationViewRoute(): string
    {
        return 'licence/bus_conversation/view';
    }
}
