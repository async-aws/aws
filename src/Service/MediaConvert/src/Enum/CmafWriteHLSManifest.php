<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to ENABLED, an Apple HLS manifest will be generated for this output.
 */
final class CmafWriteHLSManifest
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
