<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailConverseContentQualifier
{
    public const GROUNDING_SOURCE = 'grounding_source';
    public const GUARD_CONTENT = 'guard_content';
    public const QUERY = 'query';

    public static function exists(string $value): bool
    {
        return isset([
            self::GROUNDING_SOURCE => true,
            self::GUARD_CONTENT => true,
            self::QUERY => true,
        ][$value]);
    }
}
