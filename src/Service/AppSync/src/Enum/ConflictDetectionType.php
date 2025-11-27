<?php

namespace AsyncAws\AppSync\Enum;

final class ConflictDetectionType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
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
