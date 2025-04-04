<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class ToolResultStatus
{
    public const ERROR = 'error';
    public const SUCCESS = 'success';

    public static function exists(string $value): bool
    {
        return isset([
            self::ERROR => true,
            self::SUCCESS => true,
        ][$value]);
    }
}
