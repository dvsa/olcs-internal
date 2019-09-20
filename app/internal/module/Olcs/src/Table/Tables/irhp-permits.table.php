<?php

use Common\Util\Escape;

return [
    'variables' => [
        'title' => 'Permits',
        'id' => 'permits-table',
        'empty_message' => 'There are no permit records to display'
    ],
    'settings' => [
        'crud' => [
            'actions' => [
                'terminate' => ['requireRows' => true, 'class' => 'action--secondary js-require--one'],
                'request replacement' => ['requireRows' => true, 'class' => 'action--secondary js-require--one']
            ],
        ],
        'paginate' => [
            'limit' => [
                'options' => [10, 25, 50],
            ],
        ],
    ],
    'columns' => [
        [
            'title' => 'Permit No.',
            'name' => 'permitNumber',
        ],
        [
            'title' => 'Minimum emission standard',
            'name' => 'emissionsCategory',
            'formatter' => function ($row) {
                return Escape::html($row['irhpPermitRange']['emissionsCategory']['description']);
            },
        ],
        [
            'title' => 'Issued date',
            'name' => 'issueDate',
            'formatter' => 'DateTime',
        ],
        [
            'title' => 'Country',
            'name' => 'country',
            'formatter' => function ($row) {
                return Escape::html($row['irhpPermitRange']['irhpPermitStock']['country']['countryDesc']);
            },
        ],
        [
            'title' => 'Ceased Date',
            'name' => 'expiryDate',
            'formatter' => 'DateTime',
        ],
        [
            'title' => 'Replacement',
            'name' => 'replaces',
            'formatter' => function ($row) {
                $val = is_array($row['replaces']) ? 'Yes' : 'No';
                return $val;
            },
        ],
        [
            'title' => 'Status',
            'name' => 'status',
            'formatter' => 'RefDataStatus'
        ],
        [
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ]
    ],
];