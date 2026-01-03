<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the chroma sample positioning metadata for your H.264 or H.265 output. To have MediaConvert automatically
 * determine chroma positioning: We recommend that you keep the default value, Auto. To specify center positioning:
 * Choose Force center. To specify top left positioning: Choose Force top left.
 */
final class ChromaPositionMode
{
    public const AUTO = 'AUTO';
    public const FORCE_CENTER = 'FORCE_CENTER';
    public const FORCE_TOP_LEFT = 'FORCE_TOP_LEFT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::FORCE_CENTER => true,
            self::FORCE_TOP_LEFT => true,
        ][$value]);
    }
}
