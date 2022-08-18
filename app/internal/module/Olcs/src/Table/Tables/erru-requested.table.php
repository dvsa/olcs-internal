<?php

return array(
    'variables' => array(
        'titleSingular' => 'Requested penalty',
        'title' => 'Requested penalties'
    ),
    'settings' => array(

    ),
    'columns' => array(
        array(
            'title' => 'Penalty type',
            'formatter' => function ($data) {
                return $data['siPenaltyRequestedType']['id'] . ' - ' . $data['siPenaltyRequestedType']['description'];
            },
        ),
        array(
            'title' => 'Duration',
            'name' => 'duration',
        ),
    )
);
