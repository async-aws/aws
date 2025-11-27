<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable this setting when you run a test job to estimate how many reserved transcoding slots (RTS) you need. When this
 * is enabled, MediaConvert runs your job from an on-demand queue with similar performance to what you will see with one
 * RTS in a reserved queue. This setting is disabled by default.
 */
final class SimulateReservedQueue
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
