<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose how the service does stereo downmixing. Default value: Not indicated Related setting: To have MediaConvert use
 * this value, keep the default value, Custom for the setting Downmix control. Otherwise, MediaConvert ignores Stereo
 * downmix.
 */
final class Eac3AtmosStereoDownmix
{
    public const DPL2 = 'DPL2';
    public const NOT_INDICATED = 'NOT_INDICATED';
    public const STEREO = 'STEREO';
    public const SURROUND = 'SURROUND';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
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
