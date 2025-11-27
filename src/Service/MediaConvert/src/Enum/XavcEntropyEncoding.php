<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Choose a specific entropy encoding mode only when you want to override XAVC recommendations. If you choose
 * the value auto, MediaConvert uses the mode that the XAVC file format specifies given this output's operating point.
 */
final class XavcEntropyEncoding
{
    public const AUTO = 'AUTO';
    public const CABAC = 'CABAC';
    public const CAVLC = 'CAVLC';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::CABAC => true,
            self::CAVLC => true,
        ][$value]);
    }
}
