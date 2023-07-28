<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Required. Specify whether your source content already contains Nielsen non-linear watermarks. When you set this value
 * to Watermarked, the service fails the job. Nielsen requires that you add non-linear watermarking to only clean
 * content that doesn't already have non-linear Nielsen watermarks.
 */
final class NielsenSourceWatermarkStatusType
{
    public const CLEAN = 'CLEAN';
    public const WATERMARKED = 'WATERMARKED';

    public static function exists(string $value): bool
    {
        return isset([
            self::CLEAN => true,
            self::WATERMARKED => true,
        ][$value]);
    }
}
