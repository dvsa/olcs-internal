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
            'stack' => 'irhpPermitRange->emissionsCategory->description',
            'formatter' => 'StackValue',
        ],
        [
            'title' => 'Issued date',
            'name' => 'issueDate',
            'formatter' => 'DateTime',
        ],
        [
            'title' => 'Not valid for travel to',
            'formatter' => 'ConstrainedCountriesList',
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
