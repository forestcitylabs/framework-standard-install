<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Predis\Client;
use Predis\ClientInterface;

use function DI\autowire;
use function DI\factory;
use function DI\get;

return [
    // Predis client.
    ClientInterface::class => autowire(Client::class)
        ->constructor(get('redis.uri')),

    // Custom clients.
    'redis.cache_client' => factory(function (string $uri, string $prefix) {
        return new Client($uri, ['prefix' => $prefix . ':cache:']);
    })
        ->parameter('uri', get('redis.uri'))
        ->parameter('prefix', get('redis.prefix')),
    'redis.session_client' => factory(function (string $uri, string $prefix) {
        return new Client($uri, ['prefix' => $prefix . ':session:']);
    })
        ->parameter('uri', get('redis.uri'))
        ->parameter('prefix', get('redis.prefix')),
];
