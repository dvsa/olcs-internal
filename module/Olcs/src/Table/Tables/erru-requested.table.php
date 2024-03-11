<?php

return [
    'variables' => [
        'titleSingular' => 'Requested penalty',
        'title' => 'Requested penalties'
    ],
    'settings' => [

    ],
    'columns' => [
        [
            'title' => 'Penalty type',
            'formatter' => function ($data) {
                return $data['siPenaltyRequestedType']['id'] . ' - ' . $data['siPenaltyRequestedType']['description'];
            },
        ],
        [
            'title' => 'Duration',
            'name' => 'duration',
        ],
    ]
];
