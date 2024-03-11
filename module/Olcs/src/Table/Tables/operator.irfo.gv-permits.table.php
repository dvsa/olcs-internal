<?php

use Common\Service\Table\Formatter\Date;

return [
    'variables' => [
        'titleSingular' => 'GV Permit',
        'title' => 'GV Permits'
    ],
    'settings' => [
        'crud' => [
            'actions' => [
                'add' => ['class' => 'govuk-button'],
            ]
        ],
        'paginate' => [
            'limit' => [
                'default' => 10,
                'options' => [10, 25, 50]
            ]
        ]
    ],
    'columns' => [
        [
            'title' => 'Permit Id',
            'isNumeric' => true,
            'formatter' => function ($data) {
                return '<a href="' . $this->generateUrl(
                    ['action' => 'details', 'id' => $data['id']],
                    'operator/irfo/gv-permits',
                    true
                ) . '" class="govuk-link js-modal-ajax">' . $data['id'] . '</a>';
            }
        ],
        [
            'title' => 'In force date',
            'formatter' => Date::class,
            'name' => 'inForceDate'
        ],
        [
            'title' => 'Type',
            'formatter' => function ($data, $column) {
                return $data['irfoGvPermitType']['description'];
            }
        ],
        [
            'title' => 'Status',
            'formatter' => function ($data, $column) {
                return $data['irfoPermitStatus']['description'];
            }
        ]
    ]
];
