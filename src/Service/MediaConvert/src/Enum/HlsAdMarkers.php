<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Ad marker for Apple HLS manifest.
 */
final class HlsAdMarkers
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const ELEMENTAL = 'ELEMENTAL';
    public const ELEMENTAL_SCTE35 = 'ELEMENTAL_SCTE35';

    public static function exists(string $value): bool
    {
        return isset([
            self::ELEMENTAL => true,
            self::ELEMENTAL_SCTE35 => true,
        ][$value]);
    }
}
