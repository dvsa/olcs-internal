<?php

return [
    'console' => [
        'router' => [
            'routes' => [
                'process-queue' => [
                    'options' => [
                        'route' => 'process-queue [<type>]',
                        'defaults' => [
                            'controller' => 'QueueController',
                            'action' => 'index'
                        ],
                    ],
                ],
                'batch-licence-status' => [
                    'options' => [
                        'route' => 'batch-licence-status [--verbose|-v]',
                        'defaults' => [
                            'controller' => 'BatchController',
                            'action' => 'licenceStatus'
                        ],
                    ],
                ],
                'batch-cns' => [
                    'options' => [
                        'route' => 'batch-cns [--verbose|-v] [--dryrun|-d]',
                        'defaults' => [
                            'controller' => 'BatchController',
                            'action' => 'continuationNotSought'
                        ],
                    ],
                ],
                'inspection-request-email' => [
                    'options' => [
                        'route' => 'inspection-request-email [--verbose|-v]',
                        'defaults' => [
                            'controller' => 'BatchController',
                            'action' => 'inspectionRequestEmail'
                        ],
                    ],
                ],
                'process-inbox' => [
                    'options' => [
                        'route' => 'process-inbox [--verbose|-v]',
                        'defaults' => [
                            'controller' => 'BatchController',
                            'action' => 'processInboxDocuments'
                        ],
                    ],
                ],
            ]
        ]
    ],
    'queue' => [
        // 'isLongRunningProcess' => true,
        'runFor' => 60
    ],
    'controllers' => [
        'invokables' => [
            'BatchController' => 'Cli\Controller\BatchController',
            'QueueController' => 'Cli\Controller\QueueController',
        ]
    ],
    'service_manager' => [
        'invokables' => [
            'BatchLicenceStatus' => 'Cli\Service\Processing\BatchLicenceStatusProcessingService',
            'BatchInspectionRequestEmail' => 'Cli\Service\Processing\BatchInspectionRequestEmailProcessingService',
            'BatchContinuationNotSought' => 'Cli\Service\Processing\ContinuationNotSought',
            'BatchInboxDocuments' => 'Cli\Service\Processing\BatchInboxDocumentsProcessingService',
            'Queue' => 'Cli\Service\Queue\QueueProcessor',
        ],
        'factories' => [
            'MessageConsumerManager' => 'Cli\Service\Queue\MessageConsumerManagerFactory',
        ],
    ],
    'message_consumer_manager' => [
        'invokables' => [
            'que_typ_cont_checklist' => 'Cli\Service\Queue\Consumer\ContinuationChecklist',
            'que_typ_ch_initial' => 'Cli\Service\Queue\Consumer\CompaniesHouse\InitialDataLoad',
            'que_typ_ch_compare' => 'Cli\Service\Queue\Consumer\CompaniesHouse\Compare',
        ]
    ],
    'business_service_manager' => [
        'invokables' => [
            'Cli\ContinuationDetail' => 'Cli\BusinessService\Service\ContinuationDetail',
            'Cli\CompaniesHouseLoad' => 'Cli\BusinessService\Service\CompaniesHouseLoad',
        ]
    ],
    'cache' => [
        'adapter' => [
            // apc_cli is not currently enabled in environments therefore change it
            'name' => 'memory',
        ]
    ],
];
