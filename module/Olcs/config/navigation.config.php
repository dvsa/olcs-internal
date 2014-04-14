<?php

return array(
    'label' => 'Home',
    'route' => 'olcsHome',
    'pages' => array(
        array(
            'label' => 'Search',
            'route' => 'search',
            'pages' => array(
                array(
                    'label' => 'Operators',
                    'route' => 'operators/operators-params',
                    'pages' => array(
                        array(
                            'label' => 'Add Conviction',
                            'route' => 'conviction',
                        )
                    )
                ),
            )
        )
    )
);
