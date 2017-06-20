<?php

return array(
    'application-name' => 'internal',
    'application_version' => (file_exists(__DIR__ . '/../version') ? file_get_contents(__DIR__ . '/../version') : ''),
    'cqrs_client' => [
        'adapter' => \Common\Service\Cqrs\Adapter\Curl::class,
        'timeout' => 60,
    ],
    //  CSFR Form settings
    'csrf' => [
        'timeout' => 5400,  //  90 min; should match to auth timeout
        'whilelist' => [
        ],
    ],
    'soflomo_purifier' => [
        'config' => [
            'Cache.SerializerPath' => sys_get_temp_dir(),
        ],
    ],
    'zfc_rbac' => require('zfc_rbac.config.php'),
);
