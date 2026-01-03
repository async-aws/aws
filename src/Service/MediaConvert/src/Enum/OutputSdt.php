<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Selects method of inserting SDT information into output stream. "Follow input SDT" copies SDT information from input
 * stream to output stream. "Follow input SDT if present" copies SDT information from input stream to output stream if
 * SDT information is present in the input, otherwise it will fall back on the user-defined values. Enter "SDT Manually"
 * means user will enter the SDT information. "No SDT" means output stream will not contain SDT information.
 */
final class OutputSdt
{
    public const SDT_FOLLOW = 'SDT_FOLLOW';
    public const SDT_FOLLOW_IF_PRESENT = 'SDT_FOLLOW_IF_PRESENT';
    public const SDT_MANUAL = 'SDT_MANUAL';
    public const SDT_NONE = 'SDT_NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::SDT_FOLLOW => true,
            self::SDT_FOLLOW_IF_PRESENT => true,
            self::SDT_MANUAL => true,
            self::SDT_NONE => true,
        ][$value]);
    }
}
