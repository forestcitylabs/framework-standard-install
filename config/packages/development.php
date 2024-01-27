<?php

declare(strict_types=1);

/*
 * This file is part of the Forest City Labs Framework package.
 * (c) Forest City Labs <https://forestcitylabs.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

use function DI\factory;

return [
    Run::class => factory(function () {
        $whoops = new Run();

        // Do not exit, we will handle it in a middleware.
        $whoops->allowQuit(false);
        $whoops->writeToOutput(false);
        $whoops->sendHttpCode(false);

        // Push the pretty page handler.
        $whoops->pushHandler(new PrettyPageHandler());

        // Return handler.
        return $whoops;
    }),
];
