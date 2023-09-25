<?php

use Common\Service\Table\Formatter\Address;

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
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'govuk-button govuk-button--secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'govuk-button govuk-button--secondary js-require--multiple', 'requireRows' => true)
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
            'name' => 'birthDate',
        ),
        array(
            'title' => 'Place of birth',
            'name' => 'birthPlace',
        ),
        array(
            'title' => 'Type',
            'name' => 'tmType',
        ),
        array(
            'title' => 'Address',
            'width' => '350px',
            'formatter' => Address::class,
            'name' => 'address'
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
