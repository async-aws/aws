<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailContextualGroundingFilterType
{
    public const GROUNDING = 'GROUNDING';
    public const RELEVANCE = 'RELEVANCE';

    public static function exists(string $value): bool
    {
        return isset([
            self::GROUNDING => true,
            self::RELEVANCE => true,
        ][$value]);
    }
}
