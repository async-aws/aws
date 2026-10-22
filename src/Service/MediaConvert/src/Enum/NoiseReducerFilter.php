<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Noise reducer filter to select one of the following spatial image filtering functions. To use this setting, you
 * must also enable Noise reducer. * Bilateral preserves edges while reducing noise. * Mean (softest), Gaussian,
 * Lanczos, and Sharpen (sharpest) do convolution filtering. * Conserve does min/max noise reduction. * Spatial does
 * frequency-domain filtering based on JND principles. * Temporal optimizes video quality for complex motion. *
 * Perceptual exploits perceptual masking, avoiding encoding bits spent on imperceptible detail. * None disables noise
 * reduction, including the perceptual filter that MediaConvert otherwise applies by default for AVC, HEVC, AV1, MPEG-2,
 * VP8, and VP9 outputs. MediaConvert doesn't apply that default to mezzanine and archival outputs (AVC-Intra, XAVC,
 * Apple ProRes, VC3, Uncompressed), to frame capture outputs, or to outputs that use Dolby Vision, which doesn't
 * support any noise reducer.
 */
final class NoiseReducerFilter
{
    public const BILATERAL = 'BILATERAL';
    public const CONSERVE = 'CONSERVE';
    public const GAUSSIAN = 'GAUSSIAN';
    public const LANCZOS = 'LANCZOS';
    public const MEAN = 'MEAN';
    public const NONE = 'NONE';
    public const PERCEPTUAL = 'PERCEPTUAL';
    public const SHARPEN = 'SHARPEN';
    public const SPATIAL = 'SPATIAL';
    public const TEMPORAL = 'TEMPORAL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BILATERAL => true,
            self::CONSERVE => true,
            self::GAUSSIAN => true,
            self::LANCZOS => true,
            self::MEAN => true,
            self::NONE => true,
            self::PERCEPTUAL => true,
            self::SHARPEN => true,
            self::SPATIAL => true,
            self::TEMPORAL => true,
        ][$value]);
    }
}
