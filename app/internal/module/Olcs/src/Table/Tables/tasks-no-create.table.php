<?php

use Common\Service\Table\Formatter\TaskCheckbox;
use Common\Service\Table\Formatter\TaskDate;
use Common\Service\Table\Formatter\TaskDescription;
use Common\Service\Table\Formatter\TaskIdentifier;
use Common\Service\Table\Formatter\TaskOwner;

return array(
    'variables' => array(
        'title' => 'Tasks',
        'titleSingular' => 'Task',
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'edit' => array('requireRows' => true, 'class' => 'govuk-button js-require--one'),
                're-assign task' => array('requireRows' => true, 'class' => 'govuk-button govuk-button--secondary js-require--multiple'),
                'close task' => array('requireRows' => true, 'class' => 'govuk-button govuk-button--secondary js-require--multiple')
            )
        ),
        'paginate' => array(
            'limit' => array(
                'options' => array(10, 25, 50)
            )
        )
    ),
    'attributes' => array(
    ),
    'columns' => array(
        array(
            'title' => 'Link',
            'formatter' => TaskIdentifier::class,
            'name' => 'link',
            'sort' => 'linkDisplay',
        ),
        array(
            'title' => 'Category',
            'name' => 'categoryName',
            'sort' => 'categoryName',
        ),
        array(
            'title' => 'Sub category',
            'class' => 'no-wrap',
            'name' => 'taskSubCategoryName',
            'sort' => 'taskSubCategoryName',
        ),
        array(
            'title' => 'Description',
            'formatter' => TaskDescription::class,
            'sort' => 'description',
        ),
        array(
            'title' => 'Date',
            'name' => 'actionDate',
            'formatter' => TaskDate::class,
            'sort' => 'actionDate',
        ),
        array(
            'title' => 'Owner',
            'formatter' => TaskOwner::class,
            'sort' => 'teamName,ownerName',
        ),
        array(
            'title' => 'Name',
            'name' => 'name',
            'sort' => 'name',
        ),
        array(
            'title' => 'markup-table-th-action', //this is a view partial from olcs-common
            'width' => 'checkbox',
            'formatter' => TaskCheckbox::class,
        )
    )
);
