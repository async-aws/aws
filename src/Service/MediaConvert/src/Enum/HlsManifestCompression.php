<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to GZIP, compresses HLS playlist.
 */
final class HlsManifestCompression
{
    public const GZIP = 'GZIP';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::GZIP => true,
            self::NONE => true,
        ][$value]);
    }
}
