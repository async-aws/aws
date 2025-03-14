<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailContentFilterConfidence
{
    public const HIGH = 'HIGH';
    public const LOW = 'LOW';
    public const MEDIUM = 'MEDIUM';
    public const NONE = 'NONE';

    public static function exists(string $value): bool
    {
        return isset([
            self::HIGH => true,
            self::LOW => true,
            self::MEDIUM => true,
            self::NONE => true,
        ][$value]);
    }
}
