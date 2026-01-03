<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the loudness measurement mode for your audio content. For music or advertisements: We recommend that you keep
 * the default value, Program. For speech or other content: We recommend that you choose Anchor. When you do,
 * MediaConvert optimizes the loudness of your output for clarify by applying speech gates.
 */
final class AacLoudnessMeasurementMode
{
    public const ANCHOR = 'ANCHOR';
    public const PROGRAM = 'PROGRAM';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ANCHOR => true,
            self::PROGRAM => true,
        ][$value]);
    }
}
