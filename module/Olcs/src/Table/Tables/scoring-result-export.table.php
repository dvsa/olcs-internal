<?php

return array(
    'variables' => array(
        'title' => 'Scoring result export'
    ),
    'settings' => array(
    ),
    'attributes' => array(
    ),
    'columns' => array(
        array(
            'title' => 'Perfmit Reg',
            'name' => 'permitRef'
        ),
        array(
            'title' => 'Application Score',
            'name' => 'applicationScore'
        ),
        array(
            'title' => 'Permit Intensity of Use',
            'name' => 'intensityOfUse'
        ),
        array(
            'title' => 'Random Factor',
            'name' => 'randomFactor'
        ),
        array(
            'title' => 'Randomised Permit Score',
            'name' => 'randomizedScore'
        ),
        array(
            'title' => 'Percentage International',
            'name' => 'internationalJourneys'
        ),
        array(
            'title' => 'Sector',
            'name' => 'sector'
        ),
        array(
            'title' => 'Restricted Countries - Requested',
            'name' => 'restrictedCountriesRequested'
        ),

           /* array(
            'title' => 'Permit Ref',
            'name' => 'permit-ref'
            'formatter' => function ($row) {
                return sprintf(
                    '%s',
                    $row['File description'],
                );
            },
        ),*/
    )
);
