<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ForestCityLabs\Framework\Command\GraphQLDumpSchemaCommand;
use ForestCityLabs\Framework\Command\GraphQLValidateSchemaCommand;
use ForestCityLabs\Framework\GraphQL\MetadataProvider;
use ForestCityLabs\Framework\GraphQL\TypeRegistry;
use ForestCityLabs\Framework\GraphQL\ValueTransformer\ChainedValueTransformer;
use ForestCityLabs\Framework\GraphQL\ValueTransformer\ValueTransformerInterface;
use ForestCityLabs\Framework\Middleware\GraphQLMiddleware;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

use function DI\add;
use function DI\autowire;
use function DI\factory;
use function DI\get;

return [
    // Middleware services.
    GraphQLMiddleware::class => autowire()
        ->constructorParameter('debug', get('app.debug')),
    MetadataProvider::class => autowire()
        ->constructorParameter('types', get('graphql.types'))
        ->constructorParameter('controllers', get('graphql.controllers')),
    Schema::class => factory(function (TypeRegistry $type_registry) {
        return new Schema([
            'query' => $type_registry->getType('Query'),
            'mutation' => $type_registry->getType('Mutation'),
            'typeLoader' => static fn (string $name): ?Type => $type_registry->getType($name),
        ]);
    }),
    ValueTransformerInterface::class => autowire(ChainedValueTransformer::class)
        ->constructor(get('graphql.value_transformers')),
    'console.commands' => add([
        get(GraphQLValidateSchemaCommand::class),
        get(GraphQLDumpSchemaCommand::class),
    ])
];
