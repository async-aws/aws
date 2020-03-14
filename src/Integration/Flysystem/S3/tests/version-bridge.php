<?php

declare(strict_types=1);

/**
 * Create necessary classes to be compatible with flysystem v1 and v2.
 */

namespace League\Flysystem {

    use AsyncAws\Core\TesT\TestCase;

    // FilesystemAdapterTestCase exists in v2
    if (!class_exists(FilesystemAdapterTestCase::class)) {
        class FilesystemAdapterTestCase extends TestCase
        {
        }
    }

    // Visibility exists in v2
    if (!class_exists(Visibility::class)) {
        final class Visibility
        {
            public const PUBLIC = 'public';
            public const PRIVATE = 'private';
        }
    }
}
