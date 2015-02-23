<?php
return array(
    'variables' => array(
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'tm-previous-history', 'subSection' => 'convictions']
        ],
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'tm-previous-history-convictions',
            'actions' => array(
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'secondary', 'requireRows' => true)
            ),
            'action_field_name' => 'formAction'
        ),
        'submission_section' => 'display',
    ),
    'attributes' => array(
        'name' => 'convictions'
    ),
    'columns' => array(
        array(
            'title' => 'Offence',
            'name' => 'offence',
        ),
        array(
            'title' => 'Conviction date',
            'name' => 'offenceDate',
            'formatter' => 'date'
        ),
        array(
            'title' => 'Name of court',
            'name' => 'courtName'
        ),
        array(
            'title' => 'Penalty',
            'name' => 'penalty',
        ),
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/checkbox]}}'
        ),
    )
);
