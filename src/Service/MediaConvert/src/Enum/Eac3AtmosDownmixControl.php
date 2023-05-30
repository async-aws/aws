<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether MediaConvert should use any downmix metadata from your input file. Keep the default value, Custom
 * (SPECIFIED) to provide downmix values in your job settings. Choose Follow source (INITIALIZE_FROM_SOURCE) to use the
 * metadata from your input. Related settings--Use these settings to specify your downmix values: Left only/Right only
 * surround (LoRoSurroundMixLevel), Left total/Right total surround (LtRtSurroundMixLevel), Left total/Right total
 * center (LtRtCenterMixLevel), Left only/Right only center (LoRoCenterMixLevel), and Stereo downmix (StereoDownmix).
 * When you keep Custom (SPECIFIED) for Downmix control (DownmixControl) and you don't specify values for the related
 * settings, MediaConvert uses default values for those settings.
 */
final class Eac3AtmosDownmixControl
{
    public const INITIALIZE_FROM_SOURCE = 'INITIALIZE_FROM_SOURCE';
    public const SPECIFIED = 'SPECIFIED';

    public static function exists(string $value): bool
    {
        return isset([
            self::INITIALIZE_FROM_SOURCE => true,
            self::SPECIFIED => true,
        ][$value]);
    }
}
