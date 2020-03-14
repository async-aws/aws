<?php

declare(strict_types=1);

namespace {
    require_once \dirname(__DIR__) . '/vendor/autoload.php';
    $file = \dirname(__DIR__) . '/vendor/league/flysystem/test-functions.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

namespace League\Flysystem {

    use PHPUnit\Framework\TestCase;

    if (!class_exists(\League\Flysystem\FilesystemAdapterTestCase::class)) {
        class FilesystemAdapterTestCase extends TestCase
        {
        }
    }
}
