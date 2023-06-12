<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the type of Nielsen watermarks that you want in your outputs. When you choose NAES 2 and NW (NAES2_AND_NW),
 * you must provide a value for the setting SID (sourceId). When you choose CBET (CBET), you must provide a value for
 * the setting CSID (cbetSourceId). When you choose NAES 2, NW, and CBET (NAES2_AND_NW_AND_CBET), you must provide
 * values for both of these settings.
 */
final class NielsenActiveWatermarkProcessType
{
    public const CBET = 'CBET';
    public const NAES2_AND_NW = 'NAES2_AND_NW';
    public const NAES2_AND_NW_AND_CBET = 'NAES2_AND_NW_AND_CBET';

    public static function exists(string $value): bool
    {
        return isset([
            self::CBET => true,
            self::NAES2_AND_NW => true,
            self::NAES2_AND_NW_AND_CBET => true,
        ][$value]);
    }
}
