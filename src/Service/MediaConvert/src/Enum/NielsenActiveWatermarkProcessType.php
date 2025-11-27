<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the type of Nielsen watermarks that you want in your outputs. When you choose NAES 2 and NW, you must provide
 * a value for the setting SID. When you choose CBET, you must provide a value for the setting CSID. When you choose
 * NAES 2, NW, and CBET, you must provide values for both of these settings.
 */
final class NielsenActiveWatermarkProcessType
{
    public const CBET = 'CBET';
    public const NAES2_AND_NW = 'NAES2_AND_NW';
    public const NAES2_AND_NW_AND_CBET = 'NAES2_AND_NW_AND_CBET';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CBET => true,
            self::NAES2_AND_NW => true,
            self::NAES2_AND_NW_AND_CBET => true,
        ][$value]);
    }
}
