<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * This setting applies only to ProRes 4444 and ProRes 4444 XQ outputs that you create from inputs that use 4:4:4 chroma
 * sampling. Set Preserve 4:4:4 sampling to allow outputs to also use 4:4:4 chroma sampling. You must specify a value
 * for this setting when your output codec profile supports 4:4:4 chroma sampling. Related Settings: For Apple ProRes
 * outputs with 4:4:4 chroma sampling: Choose Preserve 4:4:4 sampling. Use when your input has 4:4:4 chroma sampling and
 * your output codec Profile is Apple ProRes 4444 or 4444 XQ. Note that when you choose Preserve 4:4:4 sampling, you
 * cannot include any of the following Preprocessors: Dolby Vision, HDR10+, or Noise reducer.
 */
final class ProresChromaSampling
{
    public const PRESERVE_444_SAMPLING = 'PRESERVE_444_SAMPLING';
    public const SUBSAMPLE_TO_422 = 'SUBSAMPLE_TO_422';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PRESERVE_444_SAMPLING => true,
            self::SUBSAMPLE_TO_422 => true,
        ][$value]);
    }
}
