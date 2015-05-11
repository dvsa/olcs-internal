<?php

return array(
    'variables' => array(
        'action_route' => [
            'route' => 'submission_update_table',
            'params' => ['section' => 'conditions-and-undertakings', 'subSection' => 'undertakings']
        ],
        'title' => 'Undertakings'
    ),
    'settings' => array(
        'crud' => array(
            'formName' => 'undertakings',
            'actions' => array(
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'secondary', 'requireRows' => true)
            ),
            'action_field_name' => 'formAction'
        ),
        'submission_section' => 'display'
    ),
    'attributes' => array(
        'name' => 'undertakings'
    ),
    'columns' => array(
        array(
            'title' => 'No.',
            'name' => 'id'
        ),
        array(
            'title' => 'Added via',
            'formatter' => function ($data, $column, $sl) {
                $string = $sl->get('translator')->translate($data['addedVia']['description']);
                switch($data['addedVia']['id'])
                {
                    case 'cav_case':
                        $string .= isset($data['caseId']) ? ' ' . $data['caseId'] : '';
                        break;
                    case 'cav_lic':
                        $string .= isset($data['licenceId']) ? ' ' . $data['licenceId'] : '';
                        break;
                    case 'cav_app':
                        $string .= isset($data['applicationId']) ? ' ' . $data['applicationId'] : '';
                        break;
                }
                return $string;
            },
        ),
        array(
            'title' => 'Fulfilled',
            'formatter' => function ($data, $column) {
                return $data['isFulfilled'] == 'Y' ? 'Yes' : 'No';
            },
        ),
        array(
            'title' => 'Status',
            'formatter' => function ($data, $column) {
                return $data['isDraft'] == 'Y' ? 'Draft' : 'Approved';
            },
        ),
        array(
            'title' => 'Attached to',
            'formatter' => function ($data, $column, $sm) {
                $attachedTo = $data['attachedTo']['id'] == 'cat_oc' ? 'OC' : 'Licence';
                return $sm->get('translator')->translate($attachedTo);
            }
        ),
        array(
            'title' => 'S4',
            'formatter' => function ($data, $column) {
                return 'ToDo';
            }
        ),
        array(
            'title' => 'OC Address',
            'width' => '350px',
            'formatter' => 'Address',
            'name' => 'OcAddress'
        ),
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/checkbox]}}',
            'hideWhenDisabled' => true
        ),
    )
);
