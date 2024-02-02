<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * AAC Profile.
 */
final class AacCodecProfile
{
    public const HEV1 = 'HEV1';
    public const HEV2 = 'HEV2';
    public const LC = 'LC';

    public static function exists(string $value): bool
    {
        return isset([
            self::HEV1 => true,
            self::HEV2 => true,
            self::LC => true,
        ][$value]);
    }
}
