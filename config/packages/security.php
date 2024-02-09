<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ForestCityLabs\Framework\Middleware\BearerTokenAuthenticationMiddleware;
use ForestCityLabs\Framework\Middleware\SessionAuthenticationMiddleware;
use ForestCityLabs\Framework\Security\Attribute\RequiresRole;
use ForestCityLabs\Framework\Security\Attribute\RequiresScope;
use ForestCityLabs\Framework\Security\RequirementRegistry;
use ForestCityLabs\Framework\Security\RoleRegistry;
use ForestCityLabs\Framework\Security\ScopeRegistry;
use ForestCityLabs\Framework\EventListeners\SecurityPreRouteDispatchListener;

use function DI\add;
use function DI\autowire;
use function DI\get;

return [
    // Base roles and scopes.
    'security.roles' => add([]),
    'security.scopes' => add([]),
    'security.requirements' => add([
        RequiresScope::class,
        RequiresRole::class,
    ]),

    // Permission, scope and requirements registry.
    RoleRegistry::class => autowire()
        ->constructor(get('security.roles')),
    ScopeRegistry::class => autowire()
        ->constructor(get('security.scopes')),
    RequirementRegistry::class => autowire()
        ->constructor(get('security.requirements')),

    // Security middlewares.
    SessionAuthenticationMiddleware::class => autowire()
        ->constructorParameter('path_regex', get('security.session_auth_paths')),
    BearerTokenAuthenticationMiddleware::class => autowire()
        ->constructorParameter('path_regex', get('security.token_auth_paths')),

    // Event listeners.
    'event.listeners' => add([
        SecurityPreRouteDispatchListener::class,
    ]),
];
