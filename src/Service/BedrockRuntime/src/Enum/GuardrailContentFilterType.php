<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailContentFilterType
{
    public const HATE = 'HATE';
    public const INSULTS = 'INSULTS';
    public const MISCONDUCT = 'MISCONDUCT';
    public const PROMPT_ATTACK = 'PROMPT_ATTACK';
    public const SEXUAL = 'SEXUAL';
    public const VIOLENCE = 'VIOLENCE';

    public static function exists(string $value): bool
    {
        return isset([
            self::HATE => true,
            self::INSULTS => true,
            self::MISCONDUCT => true,
            self::PROMPT_ATTACK => true,
            self::SEXUAL => true,
            self::VIOLENCE => true,
        ][$value]);
    }
}
