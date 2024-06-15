<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ForestCityLabs\Framework\Command\GraphQLDumpSchemaCommand;
use ForestCityLabs\Framework\Command\GraphQLGenerateFromSchema;
use ForestCityLabs\Framework\Command\GraphQLSchemaDiffCommand;
use ForestCityLabs\Framework\Command\GraphQLValidateSchemaCommand;
use ForestCityLabs\Framework\GraphQL\MetadataProvider;
use ForestCityLabs\Framework\GraphQL\TypeRegistry;
use ForestCityLabs\Framework\GraphQL\ValueTransformer\ChainedValueTransformer;
use ForestCityLabs\Framework\GraphQL\ValueTransformer\EnumValueTransformer;
use ForestCityLabs\Framework\GraphQL\ValueTransformer\ValueTransformerInterface;
use ForestCityLabs\Framework\Middleware\GraphQLMiddleware;
use ForestCityLabs\Framework\Utility\ClassDiscovery\ScanDirectoryDiscovery;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

use function DI\add;
use function DI\autowire;
use function DI\create;
use function DI\factory;
use function DI\get;
use function DI\string;

return [
    // Configurations.
    'graphql.type_paths' => add([string('{app.project_root}/src/Entity')]),
    'graphql.controller_paths' => add([string('{app.project_root}/src/Controller')]),
    'graphql.type_discovery' => create(ScanDirectoryDiscovery::class)
        ->constructor(get('graphql.type_paths')),
    'graphql.controller_discovery' => create(ScanDirectoryDiscovery::class)
        ->constructor(get('graphql.controller_paths')),

    // Middleware services.
    GraphQLMiddleware::class => autowire()
        ->constructorParameter('debug', get('app.debug')),
    MetadataProvider::class => autowire()
        ->constructorParameter('type_discovery', get('graphql.type_discovery'))
        ->constructorParameter('controller_discovery', get('graphql.controller_discovery')),
    Schema::class => factory(function (TypeRegistry $type_registry) {
        return new Schema([
            'query' => $type_registry->getType('Query'),
            'mutation' => $type_registry->getType('Mutation'),
            'typeLoader' => static fn (string $name): ?Type => $type_registry->getType($name),
        ]);
    }),
    ValueTransformerInterface::class => autowire(ChainedValueTransformer::class)
        ->constructor(get('graphql.value_transformers')),
    GraphQLGenerateFromSchema::class => autowire()
        ->constructorParameter('schema_file', string('{app.project_root}/config/schema.graphql'))
        ->constructorParameter('entity_dir', string('{app.project_root}/src/Entity/'))
        ->constructorParameter('entity_namespace', 'Application\\Entity')
        ->constructorParameter('entity_discovery', get('graphql.type_discovery'))
        ->constructorParameter('controller_dir', string('{app.project_root}/src/Controller/'))
        ->constructorParameter('controller_namespace', 'Application\\Controller')
        ->constructorParameter('controller_discovery', get('graphql.controller_discovery')),
    GraphQLSchemaDiffCommand::class => autowire()
        ->constructorParameter('schema_file', string('{app.project_root}/config/schema.graphql')),
    'console.commands' => add([
        get(GraphQLValidateSchemaCommand::class),
        get(GraphQLDumpSchemaCommand::class),
        get(GraphQLGenerateFromSchema::class),
        get(GraphQLSchemaDiffCommand::class),
    ])
];
