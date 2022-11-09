<?php

return array(
    'variables' => array(
        'titleSingular' => 'Applied penalty',
        'title' => 'Applied penalties'
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'add' => array('class' => 'action--primary'),
                'edit' => array('requireRows' => true, 'class' => 'action--secondary js-require--one'),
                'delete' => array('requireRows' => true, 'class' => 'action--secondary js-require--one')
            )
        )
    ),
    'columns' => array(
        array(
            'title' => 'markup-table-th-action', //this is a view partial from olcs-common
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}',
            'hideWhenDisabled' => true
        ),
        array(
            'title' => 'Penalty ID',
            'isNumeric' => true,
            'formatter' => function ($data) {
                return '<a href="' . $this->generateUrl(
                    array(
                        'action' => 'edit',
                        'id' => $data['id'],
                        'si' => $data['seriousInfringement']['id']
                    ),
                    'case_penalty_applied',
                    true
                ) . '" class="govuk-link js-modal-ajax">' . $data['id'] . '</a>';
            },
            'hideWhenDisabled' => true
        ),
        array(
            'title' => 'Penalty type',
            'formatter' => function ($data) {
                return $data['siPenaltyType']['id'] . ' - ' . $data['siPenaltyType']['description'];
            },
        ),
        array(
            'title' => 'Start date',
            'formatter' => function ($data, $column) {
                $column['formatter'] = 'Date';
                return $this->callFormatter($column, $data);
            },
            'name' => 'startDate'
        ),
        array(
            'title' => 'End date',
            'formatter' => function ($data, $column) {
                $column['formatter'] = 'Date';
                return $this->callFormatter($column, $data);
            },
            'name' => 'endDate'
        ),
        array(
            'title' => 'Imposed',
            'formatter' => 'YesNo',
            'name' => 'imposed'
        )
    )
);
