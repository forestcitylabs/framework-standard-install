<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\Console\Command;
use Doctrine\DBAL\Tools\Console\ConnectionProvider;
use Doctrine\DBAL\Tools\Console\ConnectionProvider\SingleConnectionProvider;
use Doctrine\DBAL\Types\Type;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\Doctrine\UuidBinaryType;
use Ramsey\Uuid\Doctrine\UuidType;

use function DI\add;
use function DI\autowire;
use function DI\factory;
use function DI\get;

return [
    // DBAL connection.
    Connection::class => factory(function (string $database_uri) {
        $connection = DriverManager::getConnection(['url' => $database_uri]);
        $platform = $connection->getDatabasePlatform();

        // Add the uuid types to the DBAL.
        Type::addType('uuid', UuidType::class);
        Type::addType('uuid_binary', UuidBinaryType::class);
        Type::addType('uuid_binary_ordered_time', UuidBinaryOrderedTimeType::class);
        $platform->registerDoctrineTypeMapping('uuid_binary', 'binary');
        $platform->registerDoctrineTypeMapping('uuid_binary_ordered_time', 'binary');

        return $connection;
    })
        ->parameter('database_uri', get('dbal.database_uri')),

    // Connection provider for console commands.
    ConnectionProvider::class => autowire(SingleConnectionProvider::class),

    // Add console commands.
    'console.commands' => add([
        get(Command\RunSqlCommand::class),
    ]),
];
