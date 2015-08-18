<?php

$translationPrefix = 'internal-operator-unlicensed-vehicles.table';

return array(
    'variables' => array(
        'title' => 'Vehicles',
        'titleSingular' => 'Vehicle',
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'add' => array('class' => 'primary'),
                'edit' => array('requireRows' => true, 'class' => 'secondary js-require--one'),
                'delete' => array('requireRows' => true, 'class' => 'secondary js-require--multiple')
            )
        ),
        'paginate' => array(
            'limit' => array(
                'default' => 10,
                'options' => array(10, 25, 50, 100)
            )
        ),
        'useQuery' => true
    ),
    'columns' => array(
        array(
            'title' => $translationPrefix . '.vrm',
            'stack' => 'vehicle->vrm',
            'formatter' => 'StackValue',
            'action' => 'edit',
            'type' => 'Action',
        ),
        array(
            'title' => $translationPrefix . '.weight',
            'stringFormat' => '{vehicle->platedWeight} kg',
            'formatter' => 'StackValueReplacer',
            'name' => 'weight',
        ),
        array(
            'title' => $translationPrefix . '.type',
            'stack' => 'vehicle->psvType->id',
            'formatter' => 'UnlicensedVehiclePsvType',
            'name' => 'type',
        ),
        array(
            'title' => '',
            'width' => 'checkbox',
            'type' => 'Checkbox',
            'format' => '{{[elements/checkbox]}}'
        ),
    )
);
