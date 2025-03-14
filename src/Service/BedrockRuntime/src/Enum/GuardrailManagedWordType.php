<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailManagedWordType
{
    public const PROFANITY = 'PROFANITY';

    public static function exists(string $value): bool
    {
        return isset([
            self::PROFANITY => true,
        ][$value]);
    }
}
