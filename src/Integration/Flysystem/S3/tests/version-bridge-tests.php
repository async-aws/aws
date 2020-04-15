<?php

declare(strict_types=1);

/**
 * Create necessary classes for the tests to be compatible with flysystem v1 and v2.
 */

namespace League\Flysystem;

    use PHPUnit\Framework\TestCase;

    // FilesystemAdapterTestCase exists in v2
    if (!class_exists(FilesystemAdapterTestCase::class)) {
        class FilesystemAdapterTestCase extends TestCase
        {
        }
    }
