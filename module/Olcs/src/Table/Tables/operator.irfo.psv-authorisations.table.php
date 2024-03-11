<?php

use Common\Service\Table\Formatter\Date;

return [
    'variables' => [
        'titleSingular' => 'PSV Authorisation',
        'title' => 'PSV Authorisations'
    ],
    'settings' => [
        'crud' => [
            'actions' => [
                'add' => ['class' => 'govuk-button'],
                'edit' => ['requireRows' => true, 'class' => 'govuk-button govuk-button--secondary js-require--one'],
                'reset' => [
                    'requireRows' => true,
                    'class' => 'govuk-button govuk-button--secondary js-require--one',
                    'label' => 'Reset'
                ],
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
            'title' => 'markup-table-th-action', //this is a view partial from olcs-common
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ],
        [
            'title' => 'Authorisation Id',
            'isNumeric' => true,
            'formatter' => function ($data, $column) {
                return '<a href="' . $this->generateUrl(
                    ['action' => 'edit', 'id' => $data['id']],
                    'operator/irfo/psv-authorisations',
                    true
                ) . '" class="govuk-link js-modal-ajax">' . $data['id'] . '</a>';
            }
        ],
        [
            'title' => 'IRFO File Number',
            'name' => 'irfoFileNo'
        ],
        [
            'title' => 'In force date',
            'formatter' => Date::class,
            'name' => 'inForceDate'
        ],
        [
            'title' => 'Type',
            'formatter' => function ($data, $column) {
                return $data['irfoPsvAuthType']['description'];
            }
        ],
        [
            'title' => 'Status',
            'formatter' => function ($data, $column) {
                return $data['status']['description'];
            }
        ]
    ]
];
