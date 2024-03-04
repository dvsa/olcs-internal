<?php

declare(strict_types=1);

namespace Olcs\Controller\Messages;

use Olcs\Controller\Interfaces\IrhpApplicationControllerInterface;

class IrhpApplicationCreateConversationController extends AbstractCreateConversationController implements IrhpApplicationControllerInterface
{
    protected $navigationId = 'irhp_conversations';

    protected $redirectConfig = [
        'add' => [
            'route' => 'licence/irhp-application-conversation/view',
            'resultIdMap' => [
                'conversation' => 'conversation',
                'licence' => 'licence'
            ],
            'reUseParams' => true
        ]
    ];
}
