<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use FastRoute\DataGenerator as RouteDataGeneratorInterface;
use FastRoute\DataGenerator\GroupCountBased as RouteDataGenerator;
use FastRoute\Dispatcher as RouteDispatcherInterface;
use FastRoute\Dispatcher\GroupCountBased as RouteDispatcher;
use FastRoute\RouteParser as RouteParserInterface;
use FastRoute\RouteParser\Std as RouteParser;
use ForestCityLabs\Framework\Routing\DataLoader;
use ForestCityLabs\Framework\Routing\MetadataProvider;
use ForestCityLabs\Framework\Utility\ClassDiscovery\ScanDirectoryDiscovery;

use function DI\add;
use function DI\autowire;
use function DI\create;
use function DI\factory;
use function DI\get;
use function DI\string;

return [
    // Routing configuration.
    'router.paths' => add([string('{app.project_root}/src/Controller')]),
    'router.class_discovery' => create(ScanDirectoryDiscovery::class)
        ->constructor(get('router.paths')),

    // Routing services.
    RouteParserInterface::class => autowire(RouteParser::class),
    RouteDataGeneratorInterface::class => autowire(RouteDataGenerator::class),
    RouteDispatcherInterface::class => factory(function (
        DataLoader $data_loader
    ) {
        return new RouteDispatcher($data_loader->loadRoutes());
    }),
    MetadataProvider::class => autowire()
        ->constructor(get('router.class_discovery')),
];
