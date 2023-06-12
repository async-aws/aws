<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to ENABLED, a DASH MPD manifest will be generated for this output.
 */
final class CmafWriteDASHManifest
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
