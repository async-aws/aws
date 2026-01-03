<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If your input video has accurate color space metadata, or if you don't know about color space: Keep the default
 * value, Follow. MediaConvert will automatically detect your input color space. If your input video has metadata
 * indicating the wrong color space, or has missing metadata: Specify the accurate color space here. If your input video
 * is HDR 10 and the SMPTE ST 2086 Mastering Display Color Volume static metadata isn't present in your video stream, or
 * if that metadata is present but not accurate: Choose Force HDR 10. Specify correct values in the input HDR 10
 * metadata settings. For more information about HDR jobs, see https://docs.aws.amazon.com/console/mediaconvert/hdr.
 * When you specify an input color space, MediaConvert uses the following color space metadata, which includes color
 * primaries, transfer characteristics, and matrix coefficients:
 * * HDR 10: BT.2020, PQ, BT.2020 non-constant
 * * HLG 2020: BT.2020, HLG, BT.2020 non-constant
 * * P3DCI (Theater): DCIP3, SMPTE 428M, BT.709
 * * P3D65 (SDR): Display P3, sRGB, BT.709
 * * P3D65 (HDR): Display P3, PQ, BT.709.
 */
final class ColorSpace
{
    public const FOLLOW = 'FOLLOW';
    public const HDR10 = 'HDR10';
    public const HLG_2020 = 'HLG_2020';
    public const P3D65_HDR = 'P3D65_HDR';
    public const P3D65_SDR = 'P3D65_SDR';
    public const P3DCI = 'P3DCI';
    public const REC_601 = 'REC_601';
    public const REC_709 = 'REC_709';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FOLLOW => true,
            self::HDR10 => true,
            self::HLG_2020 => true,
            self::P3D65_HDR => true,
            self::P3D65_SDR => true,
            self::P3DCI => true,
            self::REC_601 => true,
            self::REC_709 => true,
        ][$value]);
    }
}
