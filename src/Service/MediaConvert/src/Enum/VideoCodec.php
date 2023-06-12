<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Type of video codec.
 */
final class VideoCodec
{
    public const AV1 = 'AV1';
    public const AVC_INTRA = 'AVC_INTRA';
    public const FRAME_CAPTURE = 'FRAME_CAPTURE';
    public const H_264 = 'H_264';
    public const H_265 = 'H_265';
    public const MPEG2 = 'MPEG2';
    public const PASSTHROUGH = 'PASSTHROUGH';
    public const PRORES = 'PRORES';
    public const VC3 = 'VC3';
    public const VP8 = 'VP8';
    public const VP9 = 'VP9';
    public const XAVC = 'XAVC';

    public static function exists(string $value): bool
    {
        return isset([
            self::AV1 => true,
            self::AVC_INTRA => true,
            self::FRAME_CAPTURE => true,
            self::H_264 => true,
            self::H_265 => true,
            self::MPEG2 => true,
            self::PASSTHROUGH => true,
            self::PRORES => true,
            self::VC3 => true,
            self::VP8 => true,
            self::VP9 => true,
            self::XAVC => true,
        ][$value]);
    }
}
