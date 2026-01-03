<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable this setting to insert I-frames at scene changes that the service automatically detects. This improves video
 * quality and is enabled by default. If this output uses QVBR, choose Transition detection for further video quality
 * improvement. For more information about QVBR, see https://docs.aws.amazon.com/console/mediaconvert/cbr-vbr-qvbr.
 */
final class H265SceneChangeDetect
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
