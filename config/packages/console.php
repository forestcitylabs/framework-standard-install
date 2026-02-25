<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ForestCityLabs\Framework\Command\Loader\CommandLoader;
use ForestCityLabs\Framework\Utility\ClassDiscovery\ChainedDiscovery;
use ForestCityLabs\Framework\Utility\ClassDiscovery\ManualDiscovery;
use ForestCityLabs\Framework\Utility\ClassDiscovery\ScanDirectoryDiscovery;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Helper;
use Symfony\Component\Console\Helper\HelperSet;

use function DI\add;
use function DI\autowire;
use function DI\create;
use function DI\get;
use function DI\string;

return [
    // Command loader.
    CommandLoaderInterface::class => autowire(CommandLoader::class)
        ->constructorParameter('discovery', get('console.class_discovery')),

    // Main application.
    Application::class => create()
        ->constructor('Forest City Labs Framework Console')
        ->method('setHelperSet', get(HelperSet::class))
        ->method('setCommandLoader', get(CommandLoaderInterface::class)),

    // Helper set.
    HelperSet::class => create()
        ->constructor(get('console.helpers')),

    // Console class discovery.
    'console.class_discovery' => create(ChainedDiscovery::class)
        ->constructor(get('console.discovery')),
    'console.discovery' => add([
        create(ManualDiscovery::class)->constructor(get('console.commands')),
        create(ScanDirectoryDiscovery::class)->constructor(get('console.paths')),
    ]),
    'console.paths' => add([
        string('{app.project_root}/src/Command'),
    ]),

    // Helpers.
    'console.helpers' => add([
        get(Helper\FormatterHelper::class),
        get(Helper\DebugFormatterHelper::class),
        get(Helper\ProcessHelper::class),
        get(Helper\QuestionHelper::class),
    ]),
];
