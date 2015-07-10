<?php
return array(
    'variables' => array(
        'id' => 'tm-details',
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'tm-details']
        ],
        'title' => 'TM details'
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'tm-details',
            'actions' => array(
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'secondary', 'requireRows' => true)
            ),
            'action_field_name' => 'formAction'
        ),
        'submission_section' => 'display',
    ),
    'attributes' => array(
        'name' => 'tm-details'
    ),
    'columns' => array(
        array(
            'title' => 'Title',
            'name' => 'title',
        ),
        array(
            'title' => 'First name',
            'name' => 'forename',
        ),
        array(
            'title' => 'Family name',
            'name' => 'familyName',
        ),
        array(
            'title' => 'DOB',
            'name' => 'dob',
            'formatter' => 'Date'
        ),
        array(
            'title' => 'Place of birth',
            'name' => 'placeOfBirth',
        ),
        array(
            'title' => 'Type',
            'name' => 'tmType',
        ),
        array(
            'title' => 'Address',
            'width' => '350px',
            'formatter' => 'Address',
            'name' => 'address'
        ),
       array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/checkbox]}}',
            'hideWhenDisabled' => true
        ),
    )
);
