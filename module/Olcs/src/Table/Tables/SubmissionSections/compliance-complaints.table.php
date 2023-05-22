<?php

return array(
    'variables' => array(
        'id' => 'compliance-complaints',
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'compliance-complaints']
        ],
        'title' => 'Compliance complaints'
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'compliance-complaints',
            'actions' => array(
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'govuk-button govuk-button--secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'govuk-button govuk-button--secondary js-require--multiple', 'requireRows' => true)
            ),
            'action_field_name' => 'formAction'
        ),
        'submission_section' => 'display'
    ),
    'columns' => array(
        array(
            'title' => 'Complaint date',
            'name' => 'complaintDate'
        ),
        array(
            'title' => 'Complainant name',
            'formatter' => function ($data) {
                return $data['complainantForename'] . ' ' . $data['complainantFamilyName'];
            },
        ),
        array(
            'title' => 'Description',
            'name' => 'description'
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
