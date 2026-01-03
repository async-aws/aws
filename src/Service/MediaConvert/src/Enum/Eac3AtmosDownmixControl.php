<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether MediaConvert should use any downmix metadata from your input file. Keep the default value, Custom to
 * provide downmix values in your job settings. Choose Follow source to use the metadata from your input. Related
 * settings--Use these settings to specify your downmix values: Left only/Right only surround, Left total/Right total
 * surround, Left total/Right total center, Left only/Right only center, and Stereo downmix. When you keep Custom for
 * Downmix control and you don't specify values for the related settings, MediaConvert uses default values for those
 * settings.
 */
final class Eac3AtmosDownmixControl
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
