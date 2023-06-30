<?php

namespace AsyncAws\CodeGenerator;

use AsyncAws\CodeGenerator\Command\GenerateCommand;
use AsyncAws\CodeGenerator\File\Cache;
use AsyncAws\CodeGenerator\File\CachedFileDumper;
use AsyncAws\CodeGenerator\File\ClassWriter;
use AsyncAws\CodeGenerator\File\ComposerWriter;
use AsyncAws\CodeGenerator\File\Location\DirectoryResolver;
use AsyncAws\CodeGenerator\File\UnusedClassCleaner;
use AsyncAws\CodeGenerator\Generator\ApiGenerator;
use Symfony\Component\Console\Application;

final class Runner
{
    public static function create(string $manifest, DirectoryResolver $directoryResolver, string $cacheDirectory): Application
    {
        $application = new Application('AsyncAws', '0.1.0');

        $cache = new Cache($cacheDirectory);
        $command = new GenerateCommand($manifest, $cache, new ClassWriter($directoryResolver, new CachedFileDumper($cache)), new ComposerWriter($directoryResolver), new ApiGenerator(), new UnusedClassCleaner($directoryResolver));
        $application->add($command);
        $application->setDefaultCommand($command->getName(), true);

        return $application;
    }
}
