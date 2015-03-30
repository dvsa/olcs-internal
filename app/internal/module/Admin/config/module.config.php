<?php

return [
    'router' => [
        'routes' => [
            'admin-dashboard' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/admin',
                    'defaults' => [
                        'controller' => 'Admin\IndexController',
                        'action' => 'index',
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'admin-scanning' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/scanning',
                            'defaults' => [
                                'controller' => 'Admin\ScanningController',
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-printing' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/printing',
                            'defaults' => [
                                'controller' => 'Admin\PrintingController',
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-publication' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/publication',
                            'defaults' => [
                                'controller' => 'Admin\PublicationController',
                                'action' => 'redirect',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'pending' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/pending[/:action][/:publication]',
                                    'constraints' => [
                                        'publication' => '[0-9]+',
                                        'action' => '[a-z]+'
                                    ],
                                    'defaults' => [
                                        'controller' => 'Admin\PublicationController',
                                        'action' => 'index'
                                    ]
                                ]
                            ],
                            'published' => [
                                'type' => 'literal',
                                'options' => [
                                    'route' => '/published',
                                    'defaults' => [
                                        'controller' => 'Admin\PublicationController',
                                        'action' => 'published'
                                    ]
                                ]
                            ],
                            'download' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/download/:publication/:docIdentifier',
                                    'defaults' => [
                                        'controller' => 'Admin\PublicationController',
                                        'action' => 'download'
                                    ]
                                ]
                            ],
                            'recipient' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/recipient[/:action][/:recipient]',
                                    'constraints' => [
                                        'recipient' => '[0-9]+',
                                        'action' => '[a-z]+'
                                    ],
                                    'defaults' => [
                                        'controller' => 'Admin\RecipientController',
                                        'action' => 'index'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'admin-my-account' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/my-account',
                            'defaults' => [
                                'controller' => 'Admin\MyDetailsController',
                                'action' => 'redirect',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'details' => [
                                'type' => 'literal',
                                'options' => [
                                    'route' => '/details',
                                    'defaults' => [
                                        'controller' => 'Admin\MyDetailsController',
                                        'action' => 'edit'
                                    ]
                                ]
                            ],
                            'change-password' => [
                                'type' => 'literal',
                                'options' => [
                                    'route' => '/password',
                                    'defaults' => [
                                        'controller' => 'Admin\MyDetailsController',
                                        'action' => 'password'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'admin-continuation' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/continuation',
                            'defaults' => [
                                'controller' => 'Admin\ContinuationController',
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-report' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/report',
                            'defaults' => [
                                'controller' => 'Admin\ReportController',
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-user-management' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/user-management',
                            'defaults' => [
                                'controller' => 'Admin\UserManagementController',
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-financial-standing' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/financial-standing[/:action][/:id][/]',
                            'constraints' => [
                                'id' => '[0-9\,]+'
                            ],
                            'defaults' => [
                                'controller' => 'Crud\FinancialStandingController',
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-public-holiday' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/public-holiday',
                            'defaults' => [
                                'controller' => 'Admin\PublicHolidayController',
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-system-message' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/system-message',
                            'defaults' => [
                                'controller' => 'Admin\SystemMessageController',
                                'action' => 'index',
                            ]
                        ],
                    ],
                    'admin-disc-printing' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/disc-printing[/success[/:success]]',
                            'defaults' => [
                                'controller' => 'Admin\DiscPrintingController',
                                'action' => 'index',
                            ],
                            'constraints' => [
                                'licence' => '[a-z]+'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'disc_prefixes' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/disc-prefixes-list',
                                    'defaults' => [
                                        'controller' => 'Admin\DiscPrintingController',
                                        'action' => 'disc-prefixes-list'
                                    ]
                                ]
                            ],
                            'disc_numbering' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/disc-numbering',
                                    'defaults' => [
                                        'controller' => 'Admin\DiscPrintingController',
                                        'action' => 'disc-numbering'
                                    ]
                                ]
                            ],
                            'disc_printing' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/confirm-disc-printing',
                                    'defaults' => [
                                        'controller' => 'Admin\DiscPrintingController',
                                        'action' => 'confirm-disc-printing'
                                    ]
                                ]
                            ],
                        ]
                    ],
                ],
            ],
        ],
    ],
    'tables' => [
        'config' => [
            __DIR__ . '/../src/Table/Tables/'
        ]
    ],
    'crud_service_manager' => [
        'invokables' => [
            'FinancialStandingCrudService' => 'Admin\Service\Crud\FinancialStandingCrudService'
        ]
    ],
    'crud-config' => [
        /**
         * Sample crud config
         'route/match/name' => [
             // Define which actions are available, and whether they require rows to be selected
             'add' => ['requireRows' => false],
             'edit' => ['requireRows' => true]
        ]*/
    ],
    /**
     * This config array contains the config for dynamic / generic controllers
     */
    'crud_controller_config' => [
        'Crud\FinancialStandingController' => [
            'index' => [
                'pageLayout' => 'admin-layout',
                'table' => 'admin-financial-standing',
                'route' => '',
                'scripts' => [
                    'table-actions'
                ]
            ],
            'add' => [
                'pageLayout' => 'admin-layout',
                'table' => 'admin-financial-standing',
                'route' => ''
            ],
            'edit' => [
                'pageLayout' => 'admin-layout',
                'table' => 'admin-financial-standing',
                'route' => ''
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            // Crud controllers
            'Crud\FinancialStandingController' => '\Common\Controller\Crud\GenericCrudControllerFactory',
        ],
        'invokables' => [
            'Admin\IndexController' => 'Admin\Controller\IndexController',
            'Admin\PrintingController' => 'Admin\Controller\PrintingController',
            'Admin\ScanningController' => 'Admin\Controller\ScanningController',
            'Admin\PublicationController' => 'Admin\Controller\PublicationController',
            'Admin\RecipientController' => 'Admin\Controller\RecipientController',
            'Admin\ContinuationController' => 'Admin\Controller\ContinuationController',
            'Admin\ReportController' => 'Admin\Controller\ReportController',
            'Admin\UserManagementController' => 'Admin\Controller\UserManagementController',
            'Admin\PublicHolidayController' => 'Admin\Controller\PublicHolidayController',
            'Admin\SystemMessageController' => 'Admin\Controller\SystemMessageController',
            'Admin\DiscPrintingController' => 'Admin\Controller\DiscPrintingController',
            'Admin\MyDetailsController' => 'Admin\Controller\MyDetailsController'
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            'admin/view' => dirname(__DIR__) . '/view',
        ]
    ],
    'service_manager' => array(
        'aliases' => [
            'user-details' => 'UserDetailsNavigation'
        ],
        'factories' => array(
            'Admin\Service\Data\DiscSequence' => 'Admin\Service\Data\DiscSequence',
            'Admin\Service\Data\GoodsDisc' => 'Admin\Service\Data\GoodsDisc',
            'Admin\Service\Data\PsvDisc' => 'Admin\Service\Data\PsvDisc',
            'UserDetailsNavigation' => 'Admin\Navigation\UserDetailsNavigationFactory',
        )
    ),
    'local_forms_path' => [__DIR__ . '/../src/Form/Forms/'],
    //-------- Start navigation -----------------
    'navigation' => array(
        'default' => array(
            include __DIR__ . '/navigation.config.php'
        ),
        'user-details' => array(
            include __DIR__ . '/navigation-user-details.config.php'
        )
    ),
    //-------- End navigation -----------------
    'local_scripts_path' => [__DIR__ . '/../assets/js/inline/'],
];
