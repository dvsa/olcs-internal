<?php
return array(
    'variables' => array(
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'transport-managers']
        ],
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'transport-managers',
            'actions' => array(
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'secondary', 'requireRows' => true)
            ),
            'action_field_name' => 'formAction'
        ),
        'submission_section' => 'display',
    ),
    'attributes' => array(
        'name' => 'transport-managers'
    ),
    'columns' => array(
        array(
            'title' => 'Name',
            'formatter' => function ($data) {
                return $data['title'] . ' ' . $data['forename'] . ' ' . $data['familyName'];
            }
        ),
        array(
            'title' => 'DOB',
            'name' => 'dob',
            'formatter' => 'date'
        ),
        array(
            'title' => 'Other Licences / Applications',
            'formatter' => function ($data) {
                $returnString = '';
                foreach ($data['otherLicences'] as $other) {
                    $returnString .= $other['licNo'] . ' / ' . $other['applicationId'] . "<br />";
                }
                return $returnString;
            },
        ),
        array(
            'title' => 'Qualifications',
            'formatter' => function ($data) {
                return implode(', ', $data['qualifications']);
            }
        ),
        array(
            'title' => 'Type',
            'name' => 'tmType'
        ),
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/checkbox]}}'
        ),
    )
);
