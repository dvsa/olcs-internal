<?php

use Common\Service\Table\Formatter\InternalConversationMessage;


return [
    'variables' => [
        'id' => 'conversation-messages-table',
        'title' => 'Messages',
        'empty_message' => 'There are no messages to display'
    ],
    'settings' => [
        'paginate' => [
            'limit' => [
                'options' => [10, 25, 50],
            ],
        ],
    ],
    'columns' => [
        [
            'name' => 'id',
            'formatter' => InternalConversationMessage::class
        ],
    ],
];
