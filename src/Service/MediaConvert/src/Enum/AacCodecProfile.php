<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the AAC profile. For the widest player compatibility and where higher bitrates are acceptable: Keep the
 * default profile, LC (AAC-LC) For improved audio performance at lower bitrates: Choose HEV1 or HEV2. HEV1 (AAC-HE v1)
 * adds spectral band replication to improve speech audio at low bitrates. HEV2 (AAC-HE v2) adds parametric stereo,
 * which optimizes for encoding stereo audio at very low bitrates. For improved audio quality at lower bitrates,
 * adaptive audio bitrate switching, and loudness control: Choose XHE.
 */
final class AacCodecProfile
{
    public const HEV1 = 'HEV1';
    public const HEV2 = 'HEV2';
    public const LC = 'LC';
    public const XHE = 'XHE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HEV1 => true,
            self::HEV2 => true,
            self::LC => true,
            self::XHE => true,
        ][$value]);
    }
}
