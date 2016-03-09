<?php

return array(
    'variables' => array(
        'title' => 'Serious Infringements'
    ),
    'settings' => array(
        'paginate' => array(
            'limit' => array(
                'default' => 10,
                'options' => array(10, 25, 50, 100)
            )
        )
    ),
    'columns' => array(
        array(
            'title' => 'ID',
            'name' => 'id',
            'formatter' => 'SeriousInfringementLink'
        ),
        array(
            'title' => 'Opposition type',
            'formatter' => 'RefData',
            'name' => 'siCategoryType'
        ),
        array(
            'title' => 'Response set',
            'formatter' => 'YesNo',
            'name' => 'responseSet'
        ),
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        )
    )
);
