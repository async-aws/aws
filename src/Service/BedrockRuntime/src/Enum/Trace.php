<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class Trace
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const ENABLED_FULL = 'ENABLED_FULL';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
            self::ENABLED_FULL => true,
        ][$value]);
    }
}
