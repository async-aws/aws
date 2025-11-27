<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Set Embedded timecode override to Use MDPM when your AVCHD input contains timecode tag data in the Modified Digital
 * Video Pack Metadata. When you do, we recommend you also set Timecode source to Embedded. Leave Embedded timecode
 * override blank, or set to None, when your input does not contain MDPM timecode.
 */
final class EmbeddedTimecodeOverride
{
    public const NONE = 'NONE';
    public const USE_MDPM = 'USE_MDPM';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::USE_MDPM => true,
        ][$value]);
    }
}
