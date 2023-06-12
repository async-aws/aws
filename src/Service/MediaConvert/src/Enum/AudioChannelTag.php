<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * You can add a tag for this mono-channel audio track to mimic its placement in a multi-channel layout. For example, if
 * this track is the left surround channel, choose Left surround (LS).
 */
final class AudioChannelTag
{
    public const C = 'C';
    public const CS = 'CS';
    public const L = 'L';
    public const LC = 'LC';
    public const LFE = 'LFE';
    public const LS = 'LS';
    public const LSD = 'LSD';
    public const R = 'R';
    public const RC = 'RC';
    public const RS = 'RS';
    public const RSD = 'RSD';
    public const TCS = 'TCS';
    public const VHC = 'VHC';
    public const VHL = 'VHL';
    public const VHR = 'VHR';

    public static function exists(string $value): bool
    {
        return isset([
            self::C => true,
            self::CS => true,
            self::L => true,
            self::LC => true,
            self::LFE => true,
            self::LS => true,
            self::LSD => true,
            self::R => true,
            self::RC => true,
            self::RS => true,
            self::RSD => true,
            self::TCS => true,
            self::VHC => true,
            self::VHL => true,
            self::VHR => true,
        ][$value]);
    }
}
