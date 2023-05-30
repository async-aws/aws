<?php

namespace AsyncAws\Lambda\Enum;

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
