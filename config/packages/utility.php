<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use ForestCityLabs\Framework\Utility\ParameterConverter\ChainedParameterConverter;
use ForestCityLabs\Framework\Utility\ParameterConverter\DateTimeParameterConverter;
use ForestCityLabs\Framework\Utility\ParameterConverter\InputTypeConverter;
use ForestCityLabs\Framework\Utility\ParameterConverter\ParameterConverterInterface;
use ForestCityLabs\Framework\Utility\ParameterConverter\UuidParameterConverter;
use ForestCityLabs\Framework\Utility\ParameterResolver\ChainedParameterResolver;
use ForestCityLabs\Framework\Utility\ParameterResolver\ContainerParameterResolver;
use ForestCityLabs\Framework\Utility\ParameterResolver\IndexedParameterResolver;
use ForestCityLabs\Framework\Utility\ParameterResolver\ParameterResolverInterface;

use function DI\add;
use function DI\create;
use function DI\factory;
use function DI\get;

return [
    // Default parameter resolvers.
    'utility.parameter_resolvers' => add([
        get(IndexedParameterResolver::class),
        get(ContainerParameterResolver::class),
    ]),

    // Default parameter converters.
    'utility.parameter_converters' => add([
        get(UuidParameterConverter::class),
        get(DateTimeParameterConverter::class),
        get(InputTypeConverter::class),
    ]),

    // Parameter converter helper.
    ParameterConverterInterface::class => create(ChainedParameterConverter::class)
        ->constructor(get('utility.parameter_converters')),

    // Parameter resolver helper.
    ParameterResolverInterface::class => create(ChainedParameterResolver::class)
        ->constructor(get('utility.parameter_resolvers')),

    // Doctrine inflector.
    Inflector::class => factory(function () {
        return InflectorFactory::create()->build();
    }),
];
