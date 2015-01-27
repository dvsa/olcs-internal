<?php

return array(
    'variables' => array(
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'operating-centres']
        ],
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'operating-centres',
            'actions' => array(
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'secondary', 'requireRows' => true)
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
            'name' => 'OcAddress'
        ),
        array(
            'title' => 'V-auth',
            'name' => 'totAuthVehicles'
        ),
        array(
            'title' => 'T-auth',
            'name' => 'totAuthTrailers'
        ),
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/checkbox]}}'
        ),
    )
);
