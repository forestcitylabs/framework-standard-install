<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ForestCityLabs\Framework\Command\CacheClearCommand;
use Psr\Cache\CacheItemPoolInterface;

use function DI\add;
use function DI\autowire;
use function DI\get;

return [
    'cache.paths' => add([]),
    'cache.pools' => add([]),

    // Auto-wire default cache pool.
    CacheItemPoolInterface::class => get('cache.pool.default'),

    // Cache clear command.
    CacheClearCommand::class => autowire()->constructor(get('cache.pools'), get('cache.paths')),

    // Console commands.
    'console.commands' => add([
        get(CacheClearCommand::class),
    ]),
];
