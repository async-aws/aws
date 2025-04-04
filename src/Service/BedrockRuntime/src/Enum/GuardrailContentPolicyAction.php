<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailContentPolicyAction
{
    public const BLOCKED = 'BLOCKED';

    public static function exists(string $value): bool
    {
        return isset([
            self::BLOCKED => true,
        ][$value]);
    }
}
