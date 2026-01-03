<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Applies only to 29.97 fps outputs. When this feature is enabled, the service will use drop-frame timecode on outputs.
 * If it is not possible to use drop-frame timecode, the system will fall back to non-drop-frame. This setting is
 * enabled by default when Timecode insertion or Timecode track is enabled.
 */
final class DropFrameTimecode
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
