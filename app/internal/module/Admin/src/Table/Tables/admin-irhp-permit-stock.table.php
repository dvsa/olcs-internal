<?php

return [
    'settings' => [
        'crud' => [
            'actions' => [
                'add' => [
                    'class' => 'action--primary',
                    'requireRows' => false
                ],
                'edit' => [
                    'requireRows' => false,
                    'class' => 'action--secondary js-require--one'
                ],
                'delete' => [
                    'requireRows' => false,
                    'class' => 'action--secondary js-require--one'
                ]
            ]
        ],
        'paginate' => [
            'limit' => [
                'default' => 25,
                'options' => [10, 25, 50]
            ],
        ]
    ],
    'attributes' => [
    ],
    'columns' => [
        [
            'title' => 'Type',
            'name' => 'irhpPermitTypeId',
            'formatter' => 'IrhpPermitStockType'
        ],
        [
            'title' => 'Country',
            'name' => 'country',
            'formatter' => function ($row) {
                return empty($row['country']['countryDesc']) ? 'N/A' : $row['country']['countryDesc'];
            },
        ],
        [
            'title' => 'Validity Period',
            'name' => 'validFrom',
            'formatter' => 'IrhpPermitStockValidity'
        ],
        [
            'title' => 'Quota',
            'name' => 'initialStock',
        ],
        [
            'title' => 'SS Visibility',
            'name' => 'hiddenSS',
            'formatter' => function ($row) {
                return $row['hiddenSs'] ? 'Hidden' : 'Visible';
            },
        ],
        [
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ],
    ]
];
