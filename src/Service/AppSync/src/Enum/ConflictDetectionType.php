<?php

namespace AsyncAws\AppSync\Enum;

final class ConflictDetectionType
{
    public const NONE = 'NONE';
    public const VERSION = 'VERSION';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::VERSION => true,
        ][$value]);
    }
}
