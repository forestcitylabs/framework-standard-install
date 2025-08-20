<?php

use ForestCityLabs\Framework\Middleware\OAuthMiddleware;
use ForestCityLabs\Framework\Security\Attribute\RequiresScope;
use ForestCityLabs\Framework\Security\OAuth\OAuthScopeRegistry;
use ForestCityLabs\Framework\Security\OAuth\OAuthServer;

use function DI\add;
use function DI\autowire;
use function DI\get;

return [
    // OAuth configuration.
    'oauth.redirect_uri' => '',
    'oauth.scopes' => add([]),
    'oauth.grants' => add([]),

    // Add oauth scope requirement.
    'security.requirements' => add([
        RequiresScope::class,
    ]),

    // OAuth services.
    OAuthServer::class => autowire()
        ->constructorParameter('grants', get('oauth.grants')),
    OAuthScopeRegistry::class => autowire()
        ->constructorParameter('scopes', get('oauth.scopes')),

    // OAuth middleware.
    OAuthMiddleware::class => autowire()
        ->constructorParameter('redirect_path', get('oauth.redirect_uri')),
];