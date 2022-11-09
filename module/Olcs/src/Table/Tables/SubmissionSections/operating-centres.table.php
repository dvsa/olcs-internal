<?php

return array(
    'variables' => array(
        'id' => 'operating-centres',
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'operating-centres']
        ],
        'title' => 'Operating centres'
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'operating-centres',
            'actions' => array(
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'action--secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'action--secondary js-require--multiple', 'requireRows' => true)
            ),
            'action_field_name' => 'formAction'
        ),
        'submission_section' => 'display'
    ),
    'columns' => array(
        array(
            'title' => 'Address',
            'width' => '350px',
            'formatter' => 'Address',
            'addressFields' => 'FULL',
            'name' => 'OcAddress'
        ),
        array(
            'title' => 'Total V-auth',
            'name' => 'totAuthVehicles'
        ),
        array(
            'title' => 'Total T-auth',
            'name' => 'totAuthTrailers'
        ),
        array(
            'type' => 'Checkbox',
            'title' => 'markup-table-th-action', //this is a view partial from olcs-common
            'width' => 'checkbox',
            'format' => '{{[elements/checkbox]}}',
            'hideWhenDisabled' => true
        ),
    )
);
