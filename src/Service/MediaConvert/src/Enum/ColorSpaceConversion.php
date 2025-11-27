<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the color space you want for this output. The service supports conversion between HDR formats, between SDR
 * formats, from SDR to HDR, and from HDR to SDR. SDR to HDR conversion doesn't upgrade the dynamic range. The converted
 * video has an HDR format, but visually appears the same as an unconverted output. HDR to SDR conversion uses tone
 * mapping to approximate the outcome of manually regrading from HDR to SDR. When you specify an output color space,
 * MediaConvert uses the following color space metadata, which includes color primaries, transfer characteristics, and
 * matrix coefficients:
 * * HDR 10: BT.2020, PQ, BT.2020 non-constant
 * * HLG 2020: BT.2020, HLG, BT.2020 non-constant
 * * P3DCI (Theater): DCIP3, SMPTE 428M, BT.709
 * * P3D65 (SDR): Display P3, sRGB, BT.709
 * * P3D65 (HDR): Display P3, PQ, BT.709.
 */
final class ColorSpaceConversion
{
    public const FORCE_601 = 'FORCE_601';
    public const FORCE_709 = 'FORCE_709';
    public const FORCE_HDR10 = 'FORCE_HDR10';
    public const FORCE_HLG_2020 = 'FORCE_HLG_2020';
    public const FORCE_P3D65_HDR = 'FORCE_P3D65_HDR';
    public const FORCE_P3D65_SDR = 'FORCE_P3D65_SDR';
    public const FORCE_P3DCI = 'FORCE_P3DCI';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FORCE_601 => true,
            self::FORCE_709 => true,
            self::FORCE_HDR10 => true,
            self::FORCE_HLG_2020 => true,
            self::FORCE_P3D65_HDR => true,
            self::FORCE_P3D65_SDR => true,
            self::FORCE_P3DCI => true,
            self::NONE => true,
        ][$value]);
    }
}
