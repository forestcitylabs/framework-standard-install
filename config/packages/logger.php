<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

use function DI\autowire;
use function DI\create;
use function DI\get;
use function DI\string;

return [
    // Logger service configs.
    StreamHandler::class => create()
        ->constructor(get('logger.path')),
    LoggerInterface::class => autowire(Logger::class)
        ->constructor(string('{app.name}'))
        ->method('setHandlers', get('logger.handlers')),
];
