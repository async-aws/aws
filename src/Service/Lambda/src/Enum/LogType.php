<?php

namespace AsyncAws\Lambda\Enum;

/**
 * Set to `Tail` to include the execution log in the response.
 */
final class LogType
{
    public const NONE = 'None';
    public const TAIL = 'Tail';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::TAIL => true,
        ][$value]);
    }
}
