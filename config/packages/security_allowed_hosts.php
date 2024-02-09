<?php

declare(strict_types=1);

use ForestCityLabs\Framework\Middleware\AllowedHostMiddleware;

use function DI\add;
use function DI\autowire;
use function DI\env;
use function DI\get;

return [
    // Configuration values.
    'security.allowed_hosts' => add([
        env('TRUSTED_HOST', 'localhost'),
    ]),

    // Services.
    AllowedHostMiddleware::class => autowire()
        ->constructor(get('security.allowed_hosts')),
];
