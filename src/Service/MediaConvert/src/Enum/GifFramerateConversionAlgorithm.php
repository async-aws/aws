<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Specify how the transcoder performs framerate conversion. The default behavior is to use Drop duplicate
 * (DUPLICATE_DROP) conversion. When you choose Interpolate (INTERPOLATE) instead, the conversion produces smoother
 * motion.
 */
final class GifFramerateConversionAlgorithm
{
    public const DUPLICATE_DROP = 'DUPLICATE_DROP';
    public const INTERPOLATE = 'INTERPOLATE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DUPLICATE_DROP => true,
            self::INTERPOLATE => true,
        ][$value]);
    }
}
