<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the AAC profile. For the widest player compatibility and where higher bitrates are acceptable: Keep the
 * default profile, LC (AAC-LC) For improved audio performance at lower bitrates: Choose HEV1 or HEV2. HEV1 (AAC-HE v1)
 * adds spectral band replication to improve speech audio at low bitrates. HEV2 (AAC-HE v2) adds parametric stereo,
 * which optimizes for encoding stereo audio at very low bitrates.
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
