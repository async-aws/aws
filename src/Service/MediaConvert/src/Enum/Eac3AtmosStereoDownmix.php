<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose how the service does stereo downmixing. Default value: Not indicated (ATMOS_STORAGE_DDP_DMIXMOD_NOT_INDICATED)
 * Related setting: To have MediaConvert use this value, keep the default value, Custom (SPECIFIED) for the setting
 * Downmix control (DownmixControl). Otherwise, MediaConvert ignores Stereo downmix (StereoDownmix).
 */
final class Eac3AtmosStereoDownmix
{
    public const DPL2 = 'DPL2';
    public const NOT_INDICATED = 'NOT_INDICATED';
    public const STEREO = 'STEREO';
    public const SURROUND = 'SURROUND';

    public static function exists(string $value): bool
    {
        return isset([
            self::DPL2 => true,
            self::NOT_INDICATED => true,
            self::STEREO => true,
            self::SURROUND => true,
        ][$value]);
    }
}
