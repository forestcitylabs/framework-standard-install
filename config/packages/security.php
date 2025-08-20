<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ForestCityLabs\Framework\EventListeners\SecurityPreGraphQLFieldResolveListener;
use ForestCityLabs\Framework\Security\Attribute\RequiresRole;
use ForestCityLabs\Framework\Security\RequirementRegistry;
use ForestCityLabs\Framework\Security\RoleRegistry;
use ForestCityLabs\Framework\EventListeners\SecurityPreRouteDispatchListener;

use function DI\add;
use function DI\autowire;
use function DI\get;

return [
    // Base roles.
    'security.roles' => add([]),
    'security.requirements' => add([
        RequiresRole::class,
    ]),

    // Permission, scope and requirements registry.
    RoleRegistry::class => autowire()
        ->constructor(get('security.roles')),
    RequirementRegistry::class => autowire()
        ->constructor(get('security.requirements')),

    // Event listeners.
    'event.listeners' => add([
        SecurityPreRouteDispatchListener::class,
        SecurityPreGraphQLFieldResolveListener::class,
    ]),
];
