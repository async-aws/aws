<?php

declare(strict_types=1);

/**
 * Create necessary classes to be compatible with flysystem v1 and v2.
 */

namespace League\Flysystem {

    // Visibility exists in v2
    if (!class_exists(Visibility::class)) {
        final class Visibility
        {
            public const PUBLIC = 'public';
            public const PRIVATE = 'private';
        }
    }
}
