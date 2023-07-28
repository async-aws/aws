<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When you have a progressive segmented frame (PsF) input, use this setting to flag the input as PsF. MediaConvert
 * doesn't automatically detect PsF. Therefore, flagging your input as PsF results in better preservation of video
 * quality when you do deinterlacing and frame rate conversion. If you don't specify, the default value is Auto. Auto is
 * the correct setting for all inputs that are not PsF. Don't set this value to PsF when your input is interlaced. Doing
 * so creates horizontal interlacing artifacts.
 */
final class InputScanType
{
    public const AUTO = 'AUTO';
    public const PSF = 'PSF';

    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::PSF => true,
        ][$value]);
    }
}
