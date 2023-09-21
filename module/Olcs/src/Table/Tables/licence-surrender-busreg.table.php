<?php

use Common\Service\Table\Formatter\Date;

return array(
    'variables' => array(
        'title' => ' active bus registrations associated with this licence.'
    ),
    'attributes' => array(
        'name'=>'busRegistrations'
    ),
    'settings' =>[
        'showTotal'=>true
    ],
    'columns' => array(
        array(
            'title' => 'Reg No.',
            'formatter' => function ($data) {
                return '<a class="govuk-link" href="' . $this->generateUrl(
                    array('action' => 'index', 'busRegId' => $data['id']),
                    'licence/bus-details/service',
                    true
                ) . '">' . $data['regNo'] . '</a>';
            },
        ),
        array(
            'title' => 'Var No.',
            'isNumeric' => true,
            'name' => 'variationNo',
        ),
        array(
            'title' => 'Service No.',
            'isNumeric' => true, //mostly numeric so using the style
            'name' => 'serviceNo',
        ),
        array(
            'title' => '1st registered / cancelled',
            'formatter' => Date::class,
            'name' => 'date1stReg'
        ),
        array(
            'title' => 'Starting point',
            'name' => 'startPoint',
        ),
        array(
            'title' => 'Finishing point',
            'name' => 'finishPoint',
        ),
        array(
            'title' => 'Status',
            'name' => 'busRegStatusDesc'
        ),
    )
);
