<?php

namespace AsyncAws\AppSync\Enum;

final class BadRequestReason
{
    public const CODE_ERROR = 'CODE_ERROR';

    public static function exists(string $value): bool
    {
        return isset([
            self::CODE_ERROR => true,
        ][$value]);
    }
}
