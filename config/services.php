<?php

declare(strict_types=1);

use ForestCityLabs\Framework\GraphQL\ValueTransformer\DateTimeValueTransformer;

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use function DI\add;
use function DI\create;
use function DI\env;
use function DI\factory;
use function DI\get;
use function DI\string;

return [
    // Application configuration.
    'app.name' => 'Application',
    'app.version' => 'dev-main',
    'app.environment' => env('ENVIRONMENT'),
    'app.project_root' => realpath(__DIR__ . '/..'),
    'app.web_root' => string('{app.project_root}/public'),
    'app.debug' => factory(function (string $environment): bool {
        return (bool) ('dev' === $environment);
    })->parameter('environment', get('app.environment')),

    // Security configuration.
    'security.allowed_hosts' => add([
        env('TRUSTED_HOST', 'localhost'),
    ]),

    // GraphQL configuration.
    'graphql.types' => add([]),
    'graphql.controllers' => add([]),
    'graphql.value_transformers' => add([
        get(DateTimeValueTransformer::class),
    ]),

    // Event listeners.
    'event.listeners' => add([]),

    // Cache configuration.
    'cache.paths' => add([string('{app.project_root}/var/cache/CompiledContainer.php')]),
    'cache.adapter' => create(\League\Flysystem\Local\LocalFilesystemAdapter::class)
        ->constructor(string('{app.project_root}/var/cache')),
    'cache.filesystem' => create(\League\Flysystem\Filesystem::class)
        ->constructor(get('cache.adapter')),
    'cache.pool.default' => create(\ForestCityLabs\Framework\Cache\Pool\FilesystemCachePool::class)
        ->constructor(get('cache.filesystem')),
    'cache.pools' => add([
        get('cache.pool.default'),
    ]),

    // Session configuration.
    'session.filesystem.adapter' => create(\League\Flysystem\Local\LocalFilesystemAdapter::class)
        ->constructor(string('{app.project_root}/var/sessions')),
    'session.filesystem' => create(\League\Flysystem\Filesystem::class)
        ->constructor(get('session.filesystem.adapter')),
    'session.driver' => create(\ForestCityLabs\Framework\Session\Driver\FilesystemSessionDriver::class)
        ->constructor(get('session.filesystem')),

    // Logger configuration.
    'logger.path' => string('{app.project_root}/var/log/{app.environment}.log'),
    'logger.handlers' => add([
        get(\Monolog\Handler\StreamHandler::class),
    ]),

    // DBAL configuration.
    'dbal.database_uri' => env('DATABASE_URI'),

    // ORM configuration.
    'orm.proxy_directory' => string('{app.project_root}/var/cache/doctrine'),
    'orm.entity_paths' => add([
        string('{app.project_root}/src/Entity'),
    ]),

    // Migration configuration.
    'migration.paths' => add([
        'Application\\Migrations' => string('{app.project_root}/migrations'),
    ]),

    // Router configuration.
    'router.controllers' => add([]),

    // Twig configuration.
    'twig.cache_directory' => string('{app.project_root}/var/cache/twig'),
    'twig.extensions' => add([]),
    'twig.template_directories' => add([
        string('{app.project_root}/templates'),
    ]),

    // Console configuration.
    'console.commands' => add([]),
];
