<?php

use Common\Service\Table\Formatter\ConversationMessage;


return [
    'attributes' => [
       "class" => "no-row-border"
    ],
    'variables' => [
        'id' => 'messages-list-table',
        'title' => 'Message',
        'empty_message' => 'There are no message records linked to this conversation to display'
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
            'formatter' => ConversationMessage::class,
        ],
    ],
];
