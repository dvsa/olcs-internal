<?php

declare(strict_types=1);

namespace Olcs\Controller\Messages;

class LicenceCreateConversationController extends AbstractCreateConversationController
{
    protected $navigationId = 'conversations';

    protected $redirectConfig = [
        'add' => [
            'route' => 'licence/conversation/view',
            'resultIdMap' => [
                'conversation' => 'conversation',
                'licence' => 'licence'
            ],
            'reUseParams' => true
        ]
    ];
}
