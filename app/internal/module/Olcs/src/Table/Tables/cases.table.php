<?php

use Common\Service\Table\Formatter\Date;

return array(
    'variables' => array(
        'titleSingular' => 'Case',
        'title' => 'Cases'
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'add' => array('class' => 'govuk-button'),
                'edit' => array('requireRows' => true, 'class' => 'govuk-button govuk-button--secondary js-require--one'),
                'delete' => array('requireRows' => true, 'class' => 'govuk-button govuk-button--warning js-require--one')
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
            'title' => 'Case No.',
            'formatter' => function ($row) {
                return '<a class="govuk-link" href="' . $this->generateUrl(
                    array('case' => $row['id'], 'action' => 'details'),
                    'case',
                    true
                ) . '">' . $row['id'] . '</a>';
            },
            'sort' => 'id'
        ),
        array(
            'title' => 'Case type',
            'formatter' => function ($row, $column) {
                if (isset($row['caseType']['description'])) {
                    return $this->translator->translate($row['caseType']['description']);
                } else {
                    return 'Not set';
                }
            },
            'sort' => 'caseType'
        ),
        array(
            'title' => 'Created',
            'formatter' => Date::class,
            'name' => 'createdOn',
            'sort' => 'createdOn'
        ),
        array(
            'title' => 'Closed',
            'formatter' => Date::class,
            'name' => 'closedDate',
            'sort' => 'closedDate'
        ),
        array(
            'title' => 'Description',
            'formatter' => \Common\Service\Table\Formatter\Comment::class,
            'maxlength' => 250,
            'append' => '...',
            'name' => 'description'
        ),
        array(
            'title' => 'ECMS',
            'name' => 'ecmsNo'
        ),
        array(
            'title' => 'markup-table-th-action', //this is a translation key
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ),
    )
);
