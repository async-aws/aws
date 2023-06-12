<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Set Embedded timecode override (embeddedTimecodeOverride) to Use MDPM (USE_MDPM) when your AVCHD input contains
 * timecode tag data in the Modified Digital Video Pack Metadata (MDPM). When you do, we recommend you also set Timecode
 * source (inputTimecodeSource) to Embedded (EMBEDDED). Leave Embedded timecode override blank, or set to None (NONE),
 * when your input does not contain MDPM timecode.
 */
final class EmbeddedTimecodeOverride
{
    public const NONE = 'NONE';
    public const USE_MDPM = 'USE_MDPM';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::USE_MDPM => true,
        ][$value]);
    }
}
