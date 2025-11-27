<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If the sample range metadata in your input video is accurate, or if you don't know about sample range, keep the
 * default value, Follow, for this setting. When you do, the service automatically detects your input sample range. If
 * your input video has metadata indicating the wrong sample range, specify the accurate sample range here. When you do,
 * MediaConvert ignores any sample range information in the input metadata. Regardless of whether MediaConvert uses the
 * input sample range or the sample range that you specify, MediaConvert uses the sample range for transcoding and also
 * writes it to the output metadata.
 */
final class InputSampleRange
{
    public const FOLLOW = 'FOLLOW';
    public const FULL_RANGE = 'FULL_RANGE';
    public const LIMITED_RANGE = 'LIMITED_RANGE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FOLLOW => true,
            self::FULL_RANGE => true,
            self::LIMITED_RANGE => true,
        ][$value]);
    }
}
