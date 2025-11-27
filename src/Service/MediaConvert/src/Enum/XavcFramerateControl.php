<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If you are using the console, use the Frame rate setting to specify the frame rate for this output. If you want to
 * keep the same frame rate as the input video, choose Follow source. If you want to do frame rate conversion, choose a
 * frame rate from the dropdown list. The framerates shown in the dropdown list are decimal approximations of fractions.
 */
final class XavcFramerateControl
{
    public const INITIALIZE_FROM_SOURCE = 'INITIALIZE_FROM_SOURCE';
    public const SPECIFIED = 'SPECIFIED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::INITIALIZE_FROM_SOURCE => true,
            self::SPECIFIED => true,
        ][$value]);
    }
}
