<?php

declare(strict_types=1);

use ForestCityLabs\Framework\Middleware\CorsMiddleware;

use function DI\add;
use function DI\autowire;
use function DI\get;

return [
    // CORS configuration parameters.
    'security.cors.allow_origins' => add([]),
    'security.cors.allow_methods' => add([]),
    'security.cors.allow_headers' => add([]),
    'security.cors.max_age' => 3600,

    // CORS services.
    CorsMiddleware::class => autowire()
        ->constructorParameter('allow_origins', get('security.cors.allow_origins'))
        ->constructorParameter('allow_headers', get('security.cors.allow_headers'))
        ->constructorParameter('allow_methods', get('security.cors.allow_methods'))
        ->constructorParameter('max_age', get('security.cors.max_age')),
];
