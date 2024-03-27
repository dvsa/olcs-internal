<?php

return [
    'variables' => [
        'id' => 'prohibition-history',
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'prohibition-history']
        ],
        'title' => 'Prohibition history'
    ],
    'settings' => [
        'crud' => [
            'formName' => 'prohibition-history',
            'actions' => [
                'refresh-table' => ['label' => 'Refresh table', 'class' => 'govuk-button govuk-button--secondary', 'requireRows' => false],
                'delete-row' => ['label' => 'Delete row', 'class' => 'govuk-button govuk-button--secondary js-require--multiple', 'requireRows' => true]
            ],
            'action_field_name' => 'formAction'
        ],
        'submission_section' => 'display',
    ],
    'attributes' => [
        'name' => 'prohibition-history'
    ],
    'columns' => [
        [
            'title' => 'Prohibition date',
            'name' => 'prohibitionDate'
        ],
        [
            'title' => 'Date cleared',
            'name' => 'clearedDate'
        ],
        [
            'title' => 'Vehicle',
            'name' => 'vehicle'
        ],
        [
            'title' => 'Trailer',
            'name' => 'trailer'
        ],
        [
            'title' => 'Imposed at',
            'name' => 'imposedAt'
        ],
        [
            'title' => 'Type',
            'name' => 'prohibitionType'
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
