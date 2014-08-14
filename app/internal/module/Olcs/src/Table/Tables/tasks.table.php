<?php

return array(
    'variables' => array(
        'title' => 'Tasks'
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'add' => array('class' => 'primary'),
                'edit' => array('requireRows' => true),
                'delete' => array('class' => 'warning', 'requireRows' => true)
            )
        ),
        'paginate' => array(
            'limit' => array(
                'default' => 10,
                'options' => array(10, 25, 50)
            )
        )
    ),
    'attributes' => array(
    ),
    'columns' => array(
        array(
            'title' => 'Link',
            'formatter' => 'TaskIdentifier',
        ),
        array(
            'title' => 'Category',
            'name' => 'categoryName',
        ),
        array(
            'title' => 'Sub category',
            'name' => 'subCategoryName',
        ),
        array(
            'title' => 'Description',
            'formatter' => function ($row) {
                return '<a href=#>' . $row['description'] . '</a>';
            }
        ),
        array(
            'title' => 'Date',
            'name' => 'actionDate',
            'formatter' => 'TaskDate',
        ),
        array(
            'title' => 'Owner',
            'name' => 'ownerName',
        ),
        array(
            'title' => 'Name',
            'name' => 'name',
        ),
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/checkbox]}}'
        )
    )
);
