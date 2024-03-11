<?php

use Common\Service\Table\Formatter\Address;
use Common\Service\Table\Formatter\ConditionsUndertakingsType;

return [
    'variables' => [
        'title' => 'Conditions & Undertakings'
    ],
    'columns' => [
        [
            'title' => 'No.',
            'name' => 'id'
        ],
        [
            'title' => 'lva-conditions-undertakings-table-type',
            'formatter' => ConditionsUndertakingsType::class,
        ],
        [
            'title' => 'Added via',
            'formatter' => function ($data, $column) {
                return $this->translator->translate($data['addedVia']['description']);
            },
        ],
        [
            'title' => 'Fulfilled',
            'formatter' => function ($data, $column) {
                return $data['isFulfilled'] == 'Y' ? 'Yes' : 'No';
            },
        ],
        [
            'title' => 'Status',
            'formatter' => function ($data, $column) {
                return $data['isDraft'] == 'Y' ? 'Draft' : 'Approved';
            },
        ],
        [
            'title' => 'Attached to',
            'formatter' => function ($data, $column) {
                return $this->translator->translate($data['attachedTo']['description']);
            },
        ],
        [
            'title' => 'OC address',
            'width' => '300px',
            'formatter' => function ($data, $column) {

                if (isset($data['operatingCentre']['address'])) {

                    $column['formatter'] = Address::class;

                    return $this->callFormatter($column, $data['operatingCentre']['address']);
                }

                return 'N/a';
            }
        ],
    ]
];
