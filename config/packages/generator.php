<?php

use ForestCityLabs\Framework\Command\GenerateEntityCommand;
use Nette\PhpGenerator\Printer;
use Nette\PhpGenerator\PsrPrinter;

use function DI\add;
use function DI\autowire;
use function DI\get;
use function DI\string;

return [
    Printer::class => autowire(PsrPrinter::class),
    GenerateEntityCommand::class => autowire()
        ->constructorParameter('directory', string('{app.project_root}/src/Entity'))
        ->constructorParameter('namespace', 'Application\\Entity'),

    'console.commands' => add([
        get(GenerateEntityCommand::class),
    ]),
];
