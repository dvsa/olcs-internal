<?php
return array(
    'variables' => array(
        'id' => 'statements',
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'statements']
        ],
        'title' => 'Statements'
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'statements',
            'actions' => array(
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'secondary', 'requireRows' => true)
            ),
            'action_field_name' => 'formAction'
        ),
        'submission_section' => 'display',
    ),
    'attributes' => array(
        'name' => 'statements'
    ),
    'columns' => array(
        array(
            'title' => 'Date requested',
            'formatter' => 'Date',
            'name' => 'requestedDate'
        ),
        array(
            'title' => 'Requested by',
            'formatter' => function ($data) {
                return $data['requestedBy']['title']['description'] . ' ' . $data['requestedBy']['forename'] . ' ' .
                $data['requestedBy']['familyName'];
            }
        ),
        array(
            'title' => 'Statement type',
            'formatter' => function ($data) {
                return $data['statementType'];
            },
        ),
        array(
            'title' => 'Date stopped',
            'formatter' => 'Date',
            'name' => 'stoppedDate'
        ),
        array(
            'title' => 'Requestor body',
            'formatter' => 'Comment',
            'name' => 'requestorsBody'
        ),
        array(
            'title' => 'Date issued',
            'formatter' => 'Date',
            'name' => 'issuedDate'
        ),
        array(
            'title' => 'VRM',
            'name' => 'vrm'
        ),
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/checkbox]}}',
            'hideWhenDisabled' => true
        ),
    )
);
