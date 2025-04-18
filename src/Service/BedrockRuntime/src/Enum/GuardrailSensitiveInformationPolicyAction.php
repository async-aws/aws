<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailSensitiveInformationPolicyAction
{
    public const ANONYMIZED = 'ANONYMIZED';
    public const BLOCKED = 'BLOCKED';

    public static function exists(string $value): bool
    {
        return isset([
            self::ANONYMIZED => true,
            self::BLOCKED => true,
        ][$value]);
    }
}
