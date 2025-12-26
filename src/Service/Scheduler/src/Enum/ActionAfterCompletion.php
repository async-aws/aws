<?php

namespace AsyncAws\Scheduler\Enum;

final class ActionAfterCompletion
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const DELETE = 'DELETE';
    public const NONE = 'NONE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DELETE => true,
            self::NONE => true,
        ][$value]);
    }
}
