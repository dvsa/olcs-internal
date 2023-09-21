<?php

use Common\Service\Table\Formatter\Date;
use Common\Service\Table\Formatter\RefData;

return array(
    'variables' => array(
        'titleSingular' => 'Application',
        'title' => 'Applications'
    ),
    'settings' => array(
        'paginate' => array(
            'limit' => array(
                'default' => 25,
                'options' => array(10, 25, 50)
            )
        ),
    ),
    'columns' => array(
        array(
            'title' => 'Licence/App No.',
            'formatter' => function ($row) {
                return '<a class="govuk-link" href="' . $this->generateUrl(
                    array('application' => $row['id']),
                    'lva-application'
                ) . '">' . $row['licence']['licNo'] .'/'. $row['id'] . '</a>';
            }
        ),
        array(
            'title' => 'Type',
            'formatter' => function ($row) {
                return $row['isVariation'] ? 'Variation' : 'New';
            }
        ),
        array(
            'title' => 'Received',
            'formatter' => Date::class,
            'name' => 'receivedDate'
        ),
        array(
            'title' => 'Status',
            'formatter' => RefData::class,
            'name' => 'status'
        ),
    )
);
