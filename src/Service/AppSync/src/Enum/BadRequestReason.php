<?php

namespace AsyncAws\AppSync\Enum;

/**
 * Provides context for the cause of the bad request. The only supported value is `CODE_ERROR`.
 */
final class BadRequestReason
{
    public const CODE_ERROR = 'CODE_ERROR';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CODE_ERROR => true,
        ][$value]);
    }
}
