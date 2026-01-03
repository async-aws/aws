<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Selects which PIDs to place EBP markers on. They can either be placed only on the video PID, or on both the video PID
 * and all audio PIDs. Only applicable when EBP segmentation markers are is selected (segmentationMarkers is EBP or
 * EBP_LEGACY).
 */
final class M2tsEbpPlacement
{
    public const VIDEO_AND_AUDIO_PIDS = 'VIDEO_AND_AUDIO_PIDS';
    public const VIDEO_PID = 'VIDEO_PID';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::VIDEO_AND_AUDIO_PIDS => true,
            self::VIDEO_PID => true,
        ][$value]);
    }
}
