<?php
return [
    'variables' => [
        'id' => 'oppositions',
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'oppositions']
        ],
        'title' => 'Oppositions'
    ],
    'settings' => [
        'crud' => [
            'formName' => 'oppositions',
            'actions' => [
                'refresh-table' => [
                    'label' => 'Refresh table', 'class' => 'govuk-button govuk-button--secondary', 'requireRows' => false
                ],
                'delete-row' => [
                    'label' => 'Delete row', 'class' => 'govuk-button govuk-button--secondary js-require--multiple', 'requireRows' => true
                ]
            ],
            'action_field_name' => 'formAction'
        ],
        'submission_section' => 'display',
    ],
    'attributes' => [
        'name' => 'oppositions'
    ],
    'columns' => [
        [
            'title' => 'Opposition type',
            'formatter' => function ($data) {
                return $data['oppositionType'];
            },
        ],
        [
            'title' => 'Date received',
            'name' => 'dateReceived',
            'formatter' => function ($data, $column) {
                return '<a class="govuk-link" href="' . $this->generateUrl(
                    ['action' => 'edit', 'opposition' => $data['id']],
                    'case_opposition',
                    true
                ) . '">' . $data['dateReceived'] . '</a>';
            },
        ],
        [
            'title' => 'Contact name',
            'formatter' => function ($data) {
                return $data['contactName']['forename'] . ' ' . $data['contactName']['familyName'];
            }
        ],
        [
            'title' => 'Grounds',
            'formatter' => function ($data) {
                return implode(', ', $data['grounds']);
            }
        ],
        [
            'title' => 'Valid',
            'name' => 'isValid'
        ],
        [
            'title' => 'Copied',
            'name' => 'isCopied'
        ],
        [
            'title' => 'In time',
            'name' => 'isInTime'
        ],
        [
            'title' => 'Willing to attend PI',
            'name' => 'isWillingToAttendPi',
        ],
        [
            'title' => 'Withdrawn',
            'name' => 'isWithdrawn'
        ],
        [
            'type' => 'Checkbox',
            'title' => 'markup-table-th-action', //this is a view partial from olcs-common
            'width' => 'checkbox',
            'format' => '{{[elements/checkbox]}}',
            'hideWhenDisabled' => true
        ],
    ]
];
