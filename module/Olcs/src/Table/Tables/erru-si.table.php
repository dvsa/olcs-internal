<?php

return array(
    'variables' => array(
        'titleSingular' => 'Serious Infringement',
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
            'isNumeric' => true,
            'name' => 'id',
            'formatter' => 'SeriousInfringementLink'
        ),
        array(
            'title' => 'Category',
            'formatter' => 'RefData',
            'name' => 'siCategoryType'
        ),
        array(
            'title' => 'Penalty applied',
            'formatter' => 'YesNo',
            'name' => 'responseSet'
        ),
    )
);
