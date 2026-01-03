<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enables LATM/LOAS AAC output. Note that if you use LATM/LOAS AAC in an output, you must choose "No container" for the
 * output container.
 */
final class AacRawFormat
{
    public const LATM_LOAS = 'LATM_LOAS';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::LATM_LOAS => true,
            self::NONE => true,
        ][$value]);
    }
}
