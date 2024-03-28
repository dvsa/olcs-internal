<?php

return [
    'version' => [
        'environment' => 'localdev',
        'release' => (file_exists(__DIR__ . '/../version') ? file_get_contents(__DIR__ . '/../version') : ''),
        'description' => 'olcs.localdev - Docker Compose experiment',
    ],

    // New Backend service
    'api_router' => [
        'routes' => [
            'api' => [
                'child_routes' => [
                    'backend' => [
                        'options' => [
                            // Backend service URI *Environment specific*
                            'route' => 'backend-nginx',
                        ]
                    ]
                ]
            ]
        ]
    ],

    // Service addresses
    'service_api_mapping' => [
        'endpoints' => [
            // Backend service URI *Environment specific*
            'backend' => [
                'url' => 'http://backend-nginx/',
                'options' => [
                    'adapter' => \Laminas\Http\Client\Adapter\Curl::class,
                    'timeout' => 60,
                ]
            ],
            // Postcode/Address service URI *Environment specific*
            'postcode' => [
                'url' => 'http://address.reg.olcs.dev-dvsacloud.uk/',
                'options' => [
                    'adapter' => \Laminas\Http\Client\Adapter\Curl::class,
                    'timeout' => 60,
                ]
            ],
        ]
    ],

    // Document service
    'windows_7_document_share' => [
        // File hyperlink to document
        'uri_pattern' => 'file://///<host>/OLCS/olcs/%s',
    ],
    'windows_10_document_share' => [
        'uri_pattern' => 'http://<url>/OLCS/olcs/%s',
    ],

    // Asset path, URI to olcs-static (CSS, JS, etc) *Environment specific*
    'asset_path' => 'http://127.0.0.1:7001',

    'openam' => [
        'url' => 'http://olcs-auth.olcs.gov.uk:8080/secure/',
        'cookie' => [
            'domain' => 'olcs.gov.uk',
        ]
    ],

    /**
     * Configure the location of the application log
     */
    'log' => [
        'Logger' => [
            'writers' => [
                'full' => [
                    'options' => [
                        'stream' => 'php://stdout'
                    ],
                ]
            ]
        ],
        'ExceptionLogger' => [
            'writers' => [
                'full' => [
                    'options' => [
                        'stream' => 'php://stdout'
                    ],
                ]
            ]
        ]
    ],

    // enable the virus scanning of uploaded files
    // To disable scanning comment out this section or set 'cliCommand' to ""
    'antiVirus' => [
        'cliCommand' => '',
    ],

    // Show extra debug info in flash messages
    'debug' => [
        'showApiMessages' => false
    ],

    // Shows a preview for these file extensions
    'allow_file_preview' => [
        'extensions' => [
            'images' => 'jpeg,jpg,png,tif,tiff,gif,jfif,bmp'
        ]
    ],

    'cache-encryption' => [
        'node_suffix' => 'iuweb',
        'adapter' => 'openssl',
        'options' => [
            'algo' => 'aes',
            'mode' => 'gcm',
        ],
        'secrets' => [
            'node' => 'iuweb-cache-encryption-key',
            'shared' => 'shared-cache-encryption-key',
        ],
    ],

    'query_cache' => [
        // whether the cqrs cache is enabled
        'enabled' => true,
        // sets the ttl for cqrs cache - note that these caches are also used by internal
        'ttl' => [
            \Dvsa\Olcs\Transfer\Query\CacheableMediumTermQueryInterface::class => 600, //10 minutes
            \Dvsa\Olcs\Transfer\Query\CacheableLongTermQueryInterface::class => 43200, //12 hours
        ],
    ],

    'caches' => [
        'default-cache' => [
            'adapter' => Laminas\Cache\Storage\Adapter\Redis::class,
            'options' => [
                'server' => [
                    'host' => 'redis',
                    'port' => 6379,
                ],
                'lib_options' => [
                    \Redis::OPT_SERIALIZER => \Redis::SERIALIZER_IGBINARY
                ],
                'ttl' => 3600, //one hour, likely to be overridden based on use case
                'namespace' => 'zfcache',
            ],
            'plugins' => [
                [
                    'name' => 'exception_handler',
                    'options' => [
                        'throw_exceptions' => false,
                    ],
                ]
            ],
        ],
    ],
    'html-purifier-cache-dir' => '/tmp',

    'auth' => [
        'user_unique_id_salt' => '1234',
        'realm' => 'internal',
        'session_name' => 'Identity',
        'identity_provider' => \Common\Rbac\JWTIdentityProvider::class
    ],
];
