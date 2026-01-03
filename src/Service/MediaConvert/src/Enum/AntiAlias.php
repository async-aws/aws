<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The anti-alias filter is automatically applied to all outputs. The service no longer accepts the value DISABLED for
 * AntiAlias. If you specify that in your job, the service will ignore the setting.
 */
final class AntiAlias
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
