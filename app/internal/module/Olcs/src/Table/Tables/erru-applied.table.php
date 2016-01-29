<?php

return array(
    'variables' => array(
        'title' => 'Applied penalties'
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'add' => array('class' => 'primary'),
                'edit' => array('requireRows' => true, 'class' => 'secondary js-require--one'),
                'delete' => array('requireRows' => true, 'class' => 'secondary js-require--one')
            )
        )
    ),
    'columns' => array(
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ),
        array(
            'title' => 'Penalty ID',
            'formatter' => function ($data) {
                return '<a href="' . $this->generateUrl(
                    array(
                        'action' => 'edit',
                        'id' => $data['id']
                    ),
                    'case_penalty',
                    true
                ) . '" class="js-modal-ajax">' . $data['id'] . '</a>';
            },
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
            'formatter' => function ($data) {
                return $data['imposed'] == 'Y' ? 'Yes' : 'No';
            },
        )
    )
);
