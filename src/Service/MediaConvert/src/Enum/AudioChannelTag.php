<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the QuickTime audio channel layout tags for the audio channels in this audio track. Enter channel layout tags
 * in the same order as your output's audio channel order. For example, if your output audio track has a left and a
 * right channel, enter Left (L) for the first channel and Right (R) for the second. If your output has multiple
 * single-channel audio tracks, enter a single channel layout tag for each track.
 */
final class AudioChannelTag
{
    public const C = 'C';
    public const CS = 'CS';
    public const HI = 'HI';
    public const L = 'L';
    public const LC = 'LC';
    public const LFE = 'LFE';
    public const LFE2 = 'LFE2';
    public const LS = 'LS';
    public const LSD = 'LSD';
    public const LT = 'LT';
    public const LW = 'LW';
    public const M = 'M';
    public const NAR = 'NAR';
    public const R = 'R';
    public const RC = 'RC';
    public const RS = 'RS';
    public const RSD = 'RSD';
    public const RSL = 'RSL';
    public const RSR = 'RSR';
    public const RT = 'RT';
    public const RW = 'RW';
    public const TBC = 'TBC';
    public const TBL = 'TBL';
    public const TBR = 'TBR';
    public const TCS = 'TCS';
    public const VHC = 'VHC';
    public const VHL = 'VHL';
    public const VHR = 'VHR';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::C => true,
            self::CS => true,
            self::HI => true,
            self::L => true,
            self::LC => true,
            self::LFE => true,
            self::LFE2 => true,
            self::LS => true,
            self::LSD => true,
            self::LT => true,
            self::LW => true,
            self::M => true,
            self::NAR => true,
            self::R => true,
            self::RC => true,
            self::RS => true,
            self::RSD => true,
            self::RSL => true,
            self::RSR => true,
            self::RT => true,
            self::RW => true,
            self::TBC => true,
            self::TBL => true,
            self::TBR => true,
            self::TCS => true,
            self::VHC => true,
            self::VHL => true,
            self::VHR => true,
        ][$value]);
    }
}
