#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

use AsyncAws\Build\Command\CreateCommand;

$application = new Application('build', '0.1.0');

$src = __DIR__.'/src';
$manifest = __DIR__.'/build//manifest.json';
$command = new CreateCommand($manifest, $src);

$application->add($command);
$application->setDefaultCommand($command->getName());
$application->run();
