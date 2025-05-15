<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailContextualGroundingPolicyAction
{
    public const BLOCKED = 'BLOCKED';
    public const NONE = 'NONE';

    public static function exists(string $value): bool
    {
        return isset([
            self::BLOCKED => true,
            self::NONE => true,
        ][$value]);
    }
}
