<?php

return [
    'label' => 'Admin',
    'route' => 'admin-dashboard',
    'pages' => [
        [
            'label' => 'Printing',
            'route' => 'admin-dashboard/admin-printing',
        ],
        [
            'label' => 'Disc Printing',
            'route' => 'admin-dashboard/admin-disc-printing',
        ],
        [
            'label' => 'Scanning',
            'route' => 'admin-dashboard/admin-scanning',
        ],
        [
            'id'    => 'admin-dashboard/admin-publication',
            'label' => 'Publications',
            'route' => 'admin-dashboard/admin-publication',
            'pages' => [
                [
                    'id' => 'admin-dashboard/admin-publication/pending',
                    'label' => 'Pending',
                    'route' => 'admin-dashboard/admin-publication/pending'
                ],
                [
                    'id' => 'admin-dashboard/admin-publication/published',
                    'label' => 'Published',
                    'route' => 'admin-dashboard/admin-publication/published',
                ],
                [
                    'id' => 'admin-dashboard/admin-publication/recipient',
                    'label' => 'Recipients',
                    'route' => 'admin-dashboard/admin-publication/recipient',
                ]
            ]
        ],
        [
            'id'    => 'admin-dashboard/admin-my-account',
            'label' => 'My account',
            'route' => 'admin-dashboard/admin-my-account',
            'pages' => [
                [
                    'id' => 'admin-dashboard/admin-my-account/details',
                    'label' => 'Details',
                    'route' => 'admin-dashboard/admin-my-account/details'
                ],
                [
                    'id' => 'admin-dashboard/admin-my-account/change-password',
                    'label' => 'Change password',
                    'route' => 'admin-dashboard/admin-my-account/change-password',
                ],
            ]
        ],
        [
            'label' => 'Continuations',
            'route' => 'admin-dashboard/admin-continuation',
        ],
        [
            'label' => 'Reports',
            'route' => 'admin-dashboard/admin-report',
        ],
        [
            'id'    => 'admin-dashboard/admin-user-management',
            'label' => 'User management',
            'route' => 'admin-dashboard/admin-user-management',
            'pages' => [
                [
                    'id' => 'admin-dashboard/admin-user-management/teams',
                    'label' => 'Teams',
                    'route' => 'admin-dashboard/admin-user-management/teams',
                ],
                [
                    'id' => 'admin-dashboard/admin-user-management/printers',
                    'label' => 'Printers',
                    'route' => 'admin-dashboard/admin-user-management/printers',
                ]
            ]
        ],
        [
            'label' => 'Financial standing rates',
            'route' => 'admin-dashboard/admin-financial-standing',
        ],
        [
            'label' => 'Public holidays',
            'route' => 'admin-dashboard/admin-public-holiday',
        ],
        [
            'label' => 'System messages',
            'route' => 'admin-dashboard/admin-system-message',
        ]
    ]
];
