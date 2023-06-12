<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to GZIP, compresses HLS playlist.
 */
final class HlsManifestCompression
{
    public const GZIP = 'GZIP';
    public const NONE = 'NONE';

    public static function exists(string $value): bool
    {
        return isset([
            self::GZIP => true,
            self::NONE => true,
        ][$value]);
    }
}
