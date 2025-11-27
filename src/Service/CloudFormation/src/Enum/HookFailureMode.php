<?php

namespace AsyncAws\CloudFormation\Enum;

final class HookFailureMode
{
    public const FAIL = 'FAIL';
    public const WARN = 'WARN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FAIL => true,
            self::WARN => true,
        ][$value]);
    }
}
