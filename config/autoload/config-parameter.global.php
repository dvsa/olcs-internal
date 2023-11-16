<?php
use Dvsa\LaminasConfigCloudParameters\Cast\Boolean;
use Dvsa\LaminasConfigCloudParameters\Cast\Integer;
use Dvsa\LaminasConfigCloudParameters\ParameterProvider\Aws\SecretsManager;
use Dvsa\LaminasConfigCloudParameters\ParameterProvider\Aws\ParameterStore;

return [
    'config_parameters' => [
        'providers' => [
            SecretsManager::class => [
                // Todo: will need to be parameterised once all the terraform is ready.
                'DEVAPPDA-BASE-SM-APPLICATION-SECRETS',
            ],
            ParameterStore::class => [
                '/applicationparams/da/',
            ],
        ],
        'casts' => [
            '[query_cache][enabled]' => Boolean::class,
            '[query_cache][ttl][' . \Dvsa\Olcs\Transfer\Query\CacheableMediumTermQueryInterface::class . ']' => Integer::class,
            '[query_cache][ttl][' . \Dvsa\Olcs\Transfer\Query\CacheableLongTermQueryInterface::class . ']' => Integer::class,
        ]
    ],
];
