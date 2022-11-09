<?php

use Common\Util\Escape;

return [
    'variables' => [
        'titleSingular' => 'Document Template',
        'title' => 'Document Templates',
    ],
    'settings' => [
        'paginate' => [
            'limit' => [
                'default' => 10,
                'options' => [10, 25, 50]
            ]
        ],
        'crud' => [
            'actions' => [
                'add' => [
                    'class' => 'action--primary',
                    'requireRows' => false
                ],
                'edit' => [
                    'requireRows' => false,
                    'class' => 'action--secondary js-require--one'
                ],
                'delete' => [
                    'requireRows' => false,
                    'class' => 'action--secondary js-require--one'
                ]
            ]
        ],
    ],
    'columns' => [
        [
            'title' => 'Description',
            'name' => 'description',
            'sort' => 'description',
            'formatter' => function ($row) {
                $column['formatter'] = 'DocumentDescription';
                return $this->callFormatter(
                    $column,
                    $row
                );
            }
        ],
        [
            'title' => 'Category',
            'name' => 'category',
            'sort' => 'category',
            'formatter' => function ($row) {
                return empty($row['categoryName']) ? '' : Escape::html($row['categoryName']);
            },
        ],
        [
            'title' => 'Subcategory',
            'name' => 'subCategory',
            'sort' => 'subCategory',
            'formatter' => function ($row) {
                return empty($row['subCategoryName']) ? '' : Escape::html($row['subCategoryName']);
            },
        ],
        [
            'title' => 'Identifier',
            'name' => 'filename',
            'sort' => 'filename',
            'formatter' => function ($row) {
                return Escape::html(ltrim($row['filename'], '/'));
            },
        ],
        [
            'title' => 'Edited date',
            'name' => 'lastModifiedOn',
            'sort' => 'lastModifiedOn',
            'formatter' => function ($row, $column) {
                $column['formatter'] = 'Date';
                return empty($row['lastModifiedOn']) ? 'N/A' : $this->callFormatter($column, $row);
            }
        ],
        [
            'title' => 'markup-table-th-action', //this is a view partial from olcs-common
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ],
    ]
];
