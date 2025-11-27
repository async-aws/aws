<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable this setting to insert I-frames at scene changes that the service automatically detects. This improves video
 * quality and is enabled by default.
 */
final class Mpeg2SceneChangeDetect
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
