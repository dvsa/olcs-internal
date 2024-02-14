<?php

declare(strict_types=1);

namespace Olcs\Controller\Messages;

class ApplicationCreateConversationController extends AbstractCreateConversationController
{
    protected $navigationId = 'application_conversations';

    protected $redirectConfig = [
        'add' => [
            'route' => 'lva-application/conversation/view',
            'resultIdMap' => [
                'application' => 'application',
                'conversation' => 'conversation',
            ],
            'reUseParams' => true
        ]
    ];
}
