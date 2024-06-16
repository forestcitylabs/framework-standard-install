<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ForestCityLabs\Framework\Event\ListenerProvider;
use ForestCityLabs\Framework\Utility\ClassDiscovery\ChainedDiscovery;
use ForestCityLabs\Framework\Utility\ClassDiscovery\ManualDiscovery;
use ForestCityLabs\Framework\Utility\ClassDiscovery\ScanDirectoryDiscovery;
use League\Event\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

use function DI\add;
use function DI\autowire;
use function DI\create;
use function DI\get;
use function DI\string;

return [
    // Event listeners.
    'event.listeners' => add([]),
    'event.listener_paths' => add([string('{app.project_root}/src/EventListener')]),

    // Event listener discovery.
    'event.listener_discovery.dynamic' => create(ScanDirectoryDiscovery::class)
        ->constructor(get('event.listener_paths')),
    'event.listener_discovery.static' => create(ManualDiscovery::class)
        ->constructor(get('event.listeners')),
    'event.listener_discovery.chained' => create(ChainedDiscovery::class)
        ->constructor(add([
            get('event.listener_discovery.dynamic'),
            get('event.listener_discovery.static'),
        ])),

    // Listener provider and event dispatcher.
    ListenerProviderInterface::class => autowire(ListenerProvider::class)
        ->constructor(get('event.listener_discovery.chained')),
    EventDispatcherInterface::class => autowire(EventDispatcher::class)
        ->constructor(get(ListenerProviderInterface::class)),
];
