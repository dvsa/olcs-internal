<?php
return array(
    'variables' => array(
        'id' => 'tm-qualifications',
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'tm-qualifications']
        ],
        'title' => 'Qualifications'
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'tm-qualifications',
            'actions' => array(
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'govuk-button govuk-button--secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'govuk-button govuk-button--secondary js-require--multiple', 'requireRows' => true)
            ),
            'action_field_name' => 'formAction'
        ),
        'submission_section' => 'display',
    ),
    'attributes' => array(
        'name' => 'tm-qualifications'
    ),
    'columns' => array(
        array(
            'title' => 'Type',
            'name' => 'qualificationType',
        ),
        array(
            'title' => 'Serial no.',
            'name' => 'serialNo',
        ),
        array(
            'title' => 'Date',
            'name' => 'issuedDate'
        ),
        array(
            'title' => 'Country',
            'name' => 'country',
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
