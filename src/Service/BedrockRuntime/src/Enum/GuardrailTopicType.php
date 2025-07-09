<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailTopicType
{
    public const DENY = 'DENY';

    public static function exists(string $value): bool
    {
        return isset([
            self::DENY => true,
        ][$value]);
    }
}
