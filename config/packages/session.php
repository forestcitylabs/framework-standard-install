<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ForestCityLabs\Framework\Command\SessionTableCreateCommand;
use ForestCityLabs\Framework\Command\SessionClearCommand;
use ForestCityLabs\Framework\Session\SessionDriverInterface;

use function DI\add;
use function DI\get;

return [
    // Autowire session driver.
    SessionDriverInterface::class => get('session.driver'),

    'console.commands' => add([
        get(SessionTableCreateCommand::class),
        get(SessionClearCommand::class),
    ]),
];
