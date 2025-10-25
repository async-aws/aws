<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailWordPolicyAction
{
    public const BLOCKED = 'BLOCKED';

    public static function exists(string $value): bool
    {
        return isset([
            self::BLOCKED => true,
        ][$value]);
    }
}
