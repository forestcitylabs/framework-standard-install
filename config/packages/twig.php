<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ForestCityLabs\Framework\Twig\ManifestExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use function DI\add;
use function DI\create;
use function DI\factory;
use function DI\get;

return [
    // Twig configuration.
    'twig.extensions' => add([]),
    'twig.template_directories' => add([]),
    'twig.cache_directory' => sys_get_temp_dir(),

    // Extension configuration.
    'twig.extensions.manifest.path' => null,

    // Twig environment.
    Environment::class => factory(function (
        array $template_directories,
        string $cache_directory,
        array $extensions,
        bool $debug
    ) {
        $twig = new Environment(
            new FilesystemLoader($template_directories),
            ['cache' => $cache_directory, 'debug' => $debug]
        );
        foreach ($extensions as $extension) {
            $twig->addExtension($extension);
        }

        return $twig;
    })
        ->parameter('template_directories', get('twig.template_directories'))
        ->parameter('cache_directory', get('twig.cache_directory'))
        ->parameter('extensions', get('twig.extensions'))
        ->parameter('debug', get('app.debug')),

    // Twig extensions.
    ManifestExtension::class => create()
        ->constructor(get('twig.extensions.manifest.path')),
];
