<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class StopReason
{
    public const CONTENT_FILTERED = 'content_filtered';
    public const END_TURN = 'end_turn';
    public const GUARDRAIL_INTERVENED = 'guardrail_intervened';
    public const MAX_TOKENS = 'max_tokens';
    public const STOP_SEQUENCE = 'stop_sequence';
    public const TOOL_USE = 'tool_use';

    public static function exists(string $value): bool
    {
        return isset([
            self::CONTENT_FILTERED => true,
            self::END_TURN => true,
            self::GUARDRAIL_INTERVENED => true,
            self::MAX_TOKENS => true,
            self::STOP_SEQUENCE => true,
            self::TOOL_USE => true,
        ][$value]);
    }
}
