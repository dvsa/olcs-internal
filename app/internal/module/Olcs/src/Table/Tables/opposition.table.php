<?php

return array(
    'variables' => array(
        'title' => 'Oppositions'
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'opposition',
            'actions' => array(
                'add' => array('class' => 'primary'),
                'edit' => array('requireRows' => true, 'class' => 'secondary js-require--one'),
                'generate' => array(
                    'requireRows' => true,
                    'class' => 'secondary js-require--one',
                    'label' => 'Generate Letter'
                ),
                'delete' => array('requireRows' => true, 'class' => 'secondary js-require--one')
            )
        ),
        'paginate' => array(
            'limit' => array(
                'default' => 10,
                'options' => array(10, 25, 50)
            )
        )
    ),
    'columns' => array(
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ),
        array(
            'title' => 'Date received',
            'name' => 'raisedDate',
            'formatter' => function ($data, $column) {
                $column['formatter'] = 'Date';
                return '<a href="' . $this->generateUrl(
                    array('action' => 'edit', 'opposition' => $data['id']),
                    'case_opposition',
                    true
                ) . '">' . $this->callFormatter($column, $data) . '</a>';
            },
            'sort' => 'raisedDate',
        ),
        array(
            'title' => 'Opposition type',
            'formatter' => 'RefData',
            'name' => 'oppositionType'
        ),

        array(
            'title' => 'Name',
            'formatter' => function ($data, $column) {
                return $data['opposer']['contactDetails']['person']['forename'] . ' ' .
                $data['opposer']['contactDetails']['person']['familyName'];
            }
        ),
        array(
            'title' => 'Grounds',
            'formatter' => function ($data, $column) {
                $grounds = [];
                foreach ($data['grounds'] as $ground) {
                    $grounds[] = $ground['description'];
                }

                return implode(', ', $grounds);
            }
        ),
        array(
            'title' => 'Link',
            'formatter' => function ($data, $column) {
                return '-';
            },
        ),
        array(
            'title' => 'View',
            'formatter' => function ($data, $column) {
                return '-';
            },
        ),
        array(
            'title' => 'Valid',
            'name' => 'isValid',
            'formatter' => 'RefData',
            'sort' => 'isValid'
        ),
        array(
            'title' => 'Copied',
            'name' => 'isCopied',
            'sort' => 'isCopied'
        ),
        array(
            'title' => 'In time',
            'name' => 'isInTime',
            'sort' => 'isInTime'
        ),
        array(
            'title' => 'Public Inquiry',
            'name' => 'isPublicInquiry',
            'sort' => 'isPublicInquiry'
        ),
        array(
            'title' => 'Withdrawn',
            'name' => 'isWithdrawn',
            'sort' => 'isWithdrawn'
        )
    )
);
