<?php

use AsyncAws\CodeGenerator\Command\GenerateCommand;
use AsyncAws\CodeGenerator\Generator\ApiGenerator;
use Symfony\Component\Console\Application;

$application = new Application('Async AWS', '0.1.0');

$src = getenv('ASYNC_AWS_GENERATE_SRC') ?? __DIR__ . '/src';
$manifest = getenv('ASYNC_AWS_GENERATE_MANIFEST') ?? __DIR__ . '//manifest.json';
$command = new GenerateCommand($manifest, new ApiGenerator($src));
$application->add($command);
$application->setDefaultCommand($command->getName(), true);

$application->run();
