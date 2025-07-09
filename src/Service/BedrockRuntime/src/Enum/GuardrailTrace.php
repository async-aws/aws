<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailTrace
{
    public const DISABLED = 'disabled';
    public const ENABLED = 'enabled';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
