<?php

use Common\Service\Table\Formatter\InterimVehiclesCheckbox;

return [
    'variables' => [
        'title' => 'internal.interim.vehicles.table.header',
        'within_form' => true
    ],
    'settings' => [
        'crud' => [
            'actions' => [],
            'formName' => 'vehicles'
        ],
    ],
    'columns' => [
        [
            'title' => 'internal.interim.vehicles.table.vrm',
            'name' => 'vrm',
            'formatter' => function ($data) {
                return $data['vehicle']['vrm'];
            },
        ],
        [
            'title' => 'internal.interim.vehicles.table.weight',
            'name' => 'platedWeight',
            'formatter' => function ($data) {
                return $data['vehicle']['platedWeight'];
            },
        ],
        [
            'title' => 'internal.interim.vehicles.table.listed',
            'width' => 'checkbox',
            'formatter' => InterimVehiclesCheckbox::class,
            'name' => 'listed'
        ],
    ]
];
