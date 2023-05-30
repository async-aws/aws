<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * This setting applies only to ProRes 4444 and ProRes 4444 XQ outputs that you create from inputs that use 4:4:4 chroma
 * sampling. Set Preserve 4:4:4 sampling (PRESERVE_444_SAMPLING) to allow outputs to also use 4:4:4 chroma sampling. You
 * must specify a value for this setting when your output codec profile supports 4:4:4 chroma sampling. Related
 * Settings: When you set Chroma sampling to Preserve 4:4:4 sampling (PRESERVE_444_SAMPLING), you must choose an output
 * codec profile that supports 4:4:4 chroma sampling. These values for Profile (CodecProfile) support 4:4:4 chroma
 * sampling: Apple ProRes 4444 (APPLE_PRORES_4444) or Apple ProRes 4444 XQ (APPLE_PRORES_4444_XQ). When you set Chroma
 * sampling to Preserve 4:4:4 sampling, you must disable all video preprocessors except for Nexguard file marker
 * (PartnerWatermarking). When you set Chroma sampling to Preserve 4:4:4 sampling and use framerate conversion, you must
 * set Frame rate conversion algorithm (FramerateConversionAlgorithm) to Drop duplicate (DUPLICATE_DROP).
 */
final class ProresChromaSampling
{
    public const PRESERVE_444_SAMPLING = 'PRESERVE_444_SAMPLING';
    public const SUBSAMPLE_TO_422 = 'SUBSAMPLE_TO_422';

    public static function exists(string $value): bool
    {
        return isset([
            self::PRESERVE_444_SAMPLING => true,
            self::SUBSAMPLE_TO_422 => true,
        ][$value]);
    }
}
