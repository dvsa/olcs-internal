<?php

use Common\Service\Table\Formatter\Date;

return [
    'variables' => [
        'titleSingular' => 'Imposed penalty',
        'title' => 'Imposed penalties'
    ],
    'settings' => [

    ],
    'columns' => [
        [
            'title' => 'Final decision date',
            'formatter' => function ($data, $column) {
                $column['formatter'] = Date::class;
                return $this->callFormatter($column, $data);
            },
            'name' => 'finalDecisionDate'
        ],
        [
            'title' => 'Penalty type',
            'formatter' => function ($data) {
                return $data['siPenaltyImposedType']['id'] . ' - ' . $data['siPenaltyImposedType']['description'];
            },
        ],
        [
            'title' => 'Start date',
            'formatter' => function ($data, $column) {
                $column['formatter'] = Date::class;
                return $this->callFormatter($column, $data);
            },
            'name' => 'startDate'
        ],
        [
            'title' => 'End date',
            'formatter' => function ($data, $column) {
                $column['formatter'] = Date::class;
                return $this->callFormatter($column, $data);
            },
            'name' => 'endDate'
        ],
        [
            'title' => 'Executed',
            'formatter' => function ($data) {
                return $data['executed']['description'];
            },
        ]
    ]
];
