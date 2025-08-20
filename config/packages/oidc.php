<?php

use ForestCityLabs\Framework\Middleware\OidcMiddleware;
use ForestCityLabs\Framework\Security\OAuth\OAuthServer;
use ForestCityLabs\Framework\Security\Oidc\Keystore;
use ForestCityLabs\Framework\Security\Oidc\OidcClaimRegistry;
use ForestCityLabs\Framework\Security\Oidc\OidcServer;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;

use function DI\add;
use function DI\autowire;
use function DI\factory;
use function DI\get;
use function DI\string;

return [
    // OIDC server configuration.
    'oidc.keys' => add([
        'default' => string('{app.project_root}/var/keys/oidc_private.pem'),
    ]),
    'oidc.active_key' => 'default',
    'oauth.scopes' => add(['openid' => false]),
    'oidc.claims' => add([]),
    'oidc.groups' => add([]),

    // OIDC services.
    OAuthServer::class => get(OidcServer::class),
    OidcServer::class => autowire()
        ->constructorParameter('grants', get('oauth.grants')),
    Keystore::class => autowire()
        ->constructorParameter('keys', get('oidc.keys')),
    OidcClaimRegistry::class => autowire()
        ->constructorParameter('claims', get('oidc.claims'))
        ->constructorParameter('groups', get('oidc.groups')),
    Configuration::class => factory(function (Keystore $keystore, string $active_key) {
        return Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::plainText($keystore->getKey($active_key)['private']),
            InMemory::plainText($keystore->getKey($active_key)['public']),
        );
    })
        ->parameter('active_key', get('oidc.active_key')),

    // OIDC middleware configuration.
    OidcMiddleware::class => autowire()
        ->constructorParameter('redirect_uri', get('oauth.redirect_uri')),
];