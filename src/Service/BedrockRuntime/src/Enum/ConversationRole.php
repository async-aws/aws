<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class ConversationRole
{
    public const ASSISTANT = 'assistant';
    public const USER = 'user';

    public static function exists(string $value): bool
    {
        return isset([
            self::ASSISTANT => true,
            self::USER => true,
        ][$value]);
    }
}
