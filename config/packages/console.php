<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper;
use Symfony\Component\Console\Helper\HelperSet;

use function DI\add;
use function DI\create;
use function DI\get;

return [
    // Main application.
    Application::class => create()
        ->constructor('Forest City Labs Framework Console')
        ->method('setCatchExceptions', false)
        ->method('setHelperSet', get(HelperSet::class))
        ->method('addCommands', get('console.commands')),

    // Helper set.
    HelperSet::class => create()
        ->constructor(get('console.helpers')),

    // Helpers.
    'console.helpers' => add([
        get(Helper\FormatterHelper::class),
        get(Helper\DebugFormatterHelper::class),
        get(Helper\ProcessHelper::class),
        get(Helper\QuestionHelper::class),
    ]),
];
