<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\Mapping\NamingStrategy;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Console\Command;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use ForestCityLabs\Framework\Utility\ORM\EntityListenerResolver;
use Psr\Cache\CacheItemPoolInterface;

use function DI\add;
use function DI\autowire;
use function DI\create;
use function DI\factory;
use function DI\get;

return [
    // Naming strategy and mapping driver.
    NamingStrategy::class => autowire(UnderscoreNamingStrategy::class),
    MappingDriver::class => autowire(AttributeDriver::class)
        ->constructor(get('orm.entity_paths')),

    // Configuration for the entity manager.
    Configuration::class => factory(function (
        MappingDriver $mapping_driver,
        NamingStrategy $naming_strategy,
        CacheItemPoolInterface $cache,
        EntityListenerResolver $listener_resolver,
        string $proxy_dir,
        bool $debug
    ) {
        $config = new Configuration();
        $config->setMetadataCache($cache);
        $config->setQueryCache($cache);
        $config->setResultCache($cache);
        $config->setProxyDir($proxy_dir);
        $config->setProxyNamespace('DoctrineProxies');
        $config->setAutoGenerateProxyClasses($debug);
        $config->setNamingStrategy($naming_strategy);
        $config->setMetadataDriverImpl($mapping_driver);
        $config->setEntityListenerResolver($listener_resolver);

        return $config;
    })
        ->parameter('proxy_dir', get('orm.proxy_directory'))
        ->parameter('debug', get('app.debug'))
        ->parameter('cache', get('cache.pool.doctrine')),

    // ORM entity manager.
    EntityManagerInterface::class => factory(function (
        Connection $connection,
        Configuration $configuration
    ) {
        $em = EntityManager::create($connection, $configuration);

        return $em;
    }),

    // Entity manager provider for console commands.
    EntityManagerProvider::class => autowire(SingleManagerProvider::class),

    // Wire console commands.
    Command\SchemaTool\CreateCommand::class => create()
        ->constructor(get(EntityManagerProvider::class)),
    Command\SchemaTool\UpdateCommand::class => create()
        ->constructor(get(EntityManagerProvider::class)),
    Command\SchemaTool\DropCommand::class => create()
        ->constructor(get(EntityManagerProvider::class)),
    Command\GenerateProxiesCommand::class => create()
        ->constructor(get(EntityManagerProvider::class)),
    Command\RunDqlCommand::class => create()
        ->constructor(get(EntityManagerProvider::class)),
    Command\ValidateSchemaCommand::class => create()
        ->constructor(get(EntityManagerProvider::class)),
    Command\InfoCommand::class => create()
        ->constructor(get(EntityManagerProvider::class)),
    Command\MappingDescribeCommand::class => create()
        ->constructor(get(EntityManagerProvider::class)),

    // Add console commands.
    'console.commands' => add([
        get(Command\SchemaTool\CreateCommand::class),
        get(Command\SchemaTool\UpdateCommand::class),
        get(Command\SchemaTool\DropCommand::class),
        get(Command\GenerateProxiesCommand::class),
        get(Command\RunDqlCommand::class),
        get(Command\ValidateSchemaCommand::class),
        get(Command\InfoCommand::class),
        get(Command\MappingDescribeCommand::class),
    ]),

    // Doctrine cache pool.
    'cache.pool.doctrine' => get('cache.pool.default'),
];
