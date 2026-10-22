<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Use this setting to have the service insert I-frames at scene changes that it automatically detects. For
 * AV1, scene change detection is disabled by default. Choose Enabled to insert I-frames at detected scene changes,
 * which can improve video quality. If this output uses QVBR, you can instead choose Transition detection for further
 * video quality improvement. For more information about QVBR, see
 * https://docs.aws.amazon.com/console/mediaconvert/cbr-vbr-qvbr.
 */
final class Av1SceneChangeDetect
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const TRANSITION_DETECTION = 'TRANSITION_DETECTION';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
            self::TRANSITION_DETECTION => true,
        ][$value]);
    }
}
