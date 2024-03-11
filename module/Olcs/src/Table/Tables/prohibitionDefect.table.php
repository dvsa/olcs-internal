<?php

return [
    'variables' => [
        'titleSingular' => 'Prohibition defect',
        'title' => 'Prohibition defects'
    ],
    'settings' => [
        'crud' => [
            'actions' => [
                'add' => ['class' => 'govuk-button'],
                'edit' => ['requireRows' => true, 'class' => 'govuk-button govuk-button--secondary js-require--one'],
                'delete' => ['requireRows' => true, 'class' => 'govuk-button govuk-button--warning js-require--one']
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
            'title' => 'Defect type',
            'formatter' => function ($data, $column) {
                return '<a href="' . $this->generateUrl(
                    [
                        'action' => 'edit',
                        'prohibition' => $data['prohibition']['id'],
                        'id' => $data['id']
                    ],
                    'case_prohibition_defect',
                    true
                ) . '" class="govuk-link js-modal-ajax">' . $data['defectType'] . '</a>';
            }
        ],
        [
            'title' => 'Notes',
            'formatter' => \Common\Service\Table\Formatter\Comment::class,
            'name' => 'notes',
        ]
    ]
];
