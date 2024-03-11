<?php

use Common\Service\Table\Formatter\Address;

return [
    'variables' => [
        'title' => 'Conditions & undertakings',
        'empty_message' => 'There are no conditions or undertakings'
    ],
    'settings' => [
        'crud' => [
            'formName' => 'conditions',
            'actions' => [
                'add' => ['class' => 'govuk-button', 'label' => 'Add condition or undertaking'],
                'edit' => ['requireRows' => true, 'class' => 'govuk-button govuk-button--secondary js-require--one'],
                'delete' => ['requireRows' => true, 'class' => 'govuk-button govuk-button--warning js-require--one']
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
            'title' => 'No.',
            'formatter' => function ($data, $column) {
                return '<a href="' . $this->generateUrl(
                    ['action' => 'edit', 'id' => $data['id']],
                    'case_conditions_undertakings',
                    true
                ) . '" class="govuk-link js-modal-ajax">' . $data['id'] . '</a>';
            },
            'isNumeric' => true,
            'name' => 'id'
        ],
        [
            'title' => 'Type',
            'formatter' => function ($data, $column) {
                return $this->translator->translate($data['conditionType']['description']);
            },
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
