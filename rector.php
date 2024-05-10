<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
        ->withPaths([__DIR__ . '/module', __DIR__ . '/test'])
         ->withPhpSets(php82: true);
