<?php

namespace AsyncAws\CloudFormation\Enum;

final class HookFailureMode
{
    public const FAIL = 'FAIL';
    public const WARN = 'WARN';

    public static function exists(string $value): bool
    {
        return isset([
            self::FAIL => true,
            self::WARN => true,
        ][$value]);
    }
}
