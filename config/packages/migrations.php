<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\Migrations\Configuration\Connection\ConnectionLoader;
use Doctrine\Migrations\Configuration\EntityManager\EntityManagerLoader;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\Configuration\Migration\ConfigurationLoader;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command;

use function DI\add;
use function DI\autowire;
use function DI\create;
use function DI\factory;
use function DI\get;

return [
    ConnectionLoader::class => autowire(ExistingEntityManager::class),
    ConfigurationLoader::class => factory(function (array $migration_paths) {
        return new ConfigurationArray([
            'migrations_paths' => $migration_paths,
        ]);
    })
        ->parameter('migration_paths', get('migration.paths')),

    'migration.paths' => add([]),

    DependencyFactory::class => factory([DependencyFactory::class, 'fromEntityManager']),
    EntityManagerLoader::class => autowire(ExistingEntityManager::class),

    // Wire console commands.
    Command\DiffCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\CurrentCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\DumpSchemaCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\ExecuteCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\GenerateCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\LatestCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\MigrateCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\RollupCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\StatusCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\VersionCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\UpToDateCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\SyncMetadataCommand::class => create()
        ->constructor(get(DependencyFactory::class)),
    Command\ListCommand::class => create()
        ->constructor(get(DependencyFactory::class)),

    // Add console commands.
    'console.commands' => add([
        get(Command\DiffCommand::class),
        get(Command\CurrentCommand::class),
        get(Command\DumpSchemaCommand::class),
        get(Command\ExecuteCommand::class),
        get(Command\GenerateCommand::class),
        get(Command\LatestCommand::class),
        get(Command\MigrateCommand::class),
        get(Command\RollupCommand::class),
        get(Command\StatusCommand::class),
        get(Command\VersionCommand::class),
        get(Command\UpToDateCommand::class),
        get(Command\SyncMetadataCommand::class),
        get(Command\ListCommand::class),
    ]),
];
