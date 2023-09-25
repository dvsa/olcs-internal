<?php

use Common\Service\Table\Formatter\AddressLines;

return array(
    'variables' => array(
        'id' => 'undertakings',
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
                'refresh-table' => array('label' => 'Refresh table', 'class' => 'govuk-button govuk-button--secondary', 'requireRows' => false),
                'delete-row' => array('label' => 'Delete row', 'class' => 'govuk-button govuk-button--secondary js-require--multiple', 'requireRows' => true)
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
            'width' => '8%',
            'name' => 'id'
        ),
        array(
            'title' => 'Added via',
            'width' => '8%',
            'formatter' => function ($data, $column) {
                $string = $this->translator->translate($data['addedVia']) . ' '
                    .$data['parentId'];
                return $string;
            }
        ),
        array(
            'title' => 'Fulfilled',
            'width' => '8%',
            'formatter' => function ($data, $column) {
                return $data['isFulfilled'] == 'Y' ? 'Yes' : 'No';
            },
        ),
        array(
            'title' => 'Status',
            'width' => '8%',
            'formatter' => function ($data, $column) {
                return $data['isDraft'] == 'Y' ? 'Draft' : 'Approved';
            },
        ),
        array(
            'title' => 'Attached to',
            'width' => '8%',
            'formatter' => function ($data, $column) {
                $attachedTo = $data['attachedTo'] == 'Operating Centre' ? 'OC' : $data['attachedTo'];
                return $this->translator->translate($attachedTo);
            }
        ),
        array(
            'title' => 'OC Address',
            'width' => '20%',
            'formatter' => AddressLines::class,
            'name' => 'OcAddress'
        ),
        array(
            'title' => 'Notes',
            'width' => '40%',
            'name' => 'notes',
            'formatter' => \Common\Service\Table\Formatter\Comment::class,
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
