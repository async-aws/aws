<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Profile (Mpeg2CodecProfile) to set the MPEG-2 profile for the video output.
 */
final class Mpeg2CodecProfile
{
    public const MAIN = 'MAIN';
    public const PROFILE_422 = 'PROFILE_422';

    public static function exists(string $value): bool
    {
        return isset([
            self::MAIN => true,
            self::PROFILE_422 => true,
        ][$value]);
    }
}
