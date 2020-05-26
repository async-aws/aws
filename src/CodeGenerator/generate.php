<?php

use AsyncAws\CodeGenerator\Command\GenerateCommand;
use AsyncAws\CodeGenerator\Generator\ApiGenerator;
use Symfony\Component\Console\Application;

$application = new Application('AsyncAws', '0.1.0');

$src = $_SERVER['ASYNC_AWS_GENERATE_SRC'] ?? __DIR__ . '/src';
$cache = $_SERVER['ASYNC_AWS_GENERATE_CACHE'] ?? __DIR__ . '/.async-aws.cache';
$manifest = $_SERVER['ASYNC_AWS_GENERATE_MANIFEST'] ?? __DIR__ . '/manifest.json';
$command = new GenerateCommand($manifest, $cache, new ApiGenerator($src));
$application->add($command);
$application->setDefaultCommand($command->getName(), true);

$application->run();
