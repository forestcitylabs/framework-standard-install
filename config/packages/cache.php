<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ForestCityLabs\Framework\Cache\Pool\FilesystemCachePool;
use ForestCityLabs\Framework\Command\CacheClearCommand;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem;
use Psr\Cache\CacheItemPoolInterface;

use function DI\add;
use function DI\autowire;
use function DI\create;
use function DI\get;
use function DI\string;

return [
    'cache.adapter' => create(LocalFilesystemAdapter::class)
        ->constructor(string('{app.project_root}/var/cache')),
    'cache.filesystem' => create(Filesystem::class)
        ->constructor(get('cache.adapter')),
    'cache.pool.default' => create(FilesystemCachePool::class)
        ->constructor(get('cache.filesystem')),
    'cache.pools' => add([
        get('cache.pool.default'),
    ]),
    'cache.paths' => add([]),

    // Auto-wire default cache pool.
    CacheItemPoolInterface::class => get('cache.pool.default'),

    // Cache clear command.
    CacheClearCommand::class => autowire()->constructor(get('cache.pools'), get('cache.paths')),

    // Console commands.
    'console.commands' => add([
        get(\ForestCityLabs\Framework\Command\CacheClearCommand::class),
    ]),
];
