<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Http\Factory\Guzzle\ResponseFactory;
use Http\Factory\Guzzle\StreamFactory;
use Http\Factory\Guzzle\UriFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

use function DI\autowire;

return [
    // Http factory services.
    ResponseFactoryInterface::class => autowire(ResponseFactory::class),
    StreamFactoryInterface::class => autowire(StreamFactory::class),
    UriFactoryInterface::class => autowire(UriFactory::class),
];
