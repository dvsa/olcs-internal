<?php

use Dvsa\LaminasConfigCloudParameters\ParameterProvider\Aws\SecretsManager;
use Dvsa\LaminasConfigCloudParameters\ParameterProvider\Aws\ParameterStore;

return [
    'config_parameters' => [
        'providers' => [
            SecretsManager::class => [
                // Todo: will need to be parameterised once all the terraform is ready.
                'DEVAPPDA-BASE-SM-APPLICATION-SECRETS',
                // sprintf('environment-%s-secrets', $environment),
            ],
            ParameterStore::class => [
                sprintf('applicationparams/%s', $_ENV['APP_ENV']),
            ],
        ],
    ],
];