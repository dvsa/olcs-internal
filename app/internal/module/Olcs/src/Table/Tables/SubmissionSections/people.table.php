<?php
return array(
    'variables' => array(
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'people']
        ],
        'title' => 'People',
        'id' => 'people'
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'people',
            'actions' => array(
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'govuk-button govuk-button--secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'govuk-button govuk-button--secondary js-require--multiple', 'requireRows' => true)
            ),
            'action_field_name' => 'formAction'
        ),
        'submission_section' => 'display'
    ),
    'attributes' => array(
        'name' => 'people'
    ),
    'columns' => array(
        array(
            'title' => 'Title',
            'name' => 'title'
        ),
        array(
            'title' => 'Firstname',
            'name' => 'forename'
        ),
        array(
            'title' => 'Surname',
            'name' => 'familyName'
        ),
        array(
            'title' => 'DOB',
            'name' => 'birthDate'
        ),
        array(
            'title' => 'Disqual.',
            'name' => 'disqualificationStatus'
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
