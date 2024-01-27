<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ForestCityLabs\Framework\Event\ListenerProvider;
use League\Event\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

use function DI\add;
use function DI\autowire;
use function DI\get;

return [
    // Event listeners.
    'event.listeners' => add([]),

    // Listener provider and event dispatcher.
    ListenerProviderInterface::class => autowire(ListenerProvider::class)
        ->constructor(get('event.listeners')),
    EventDispatcherInterface::class => autowire(EventDispatcher::class)
        ->constructor(get(ListenerProviderInterface::class)),
];
