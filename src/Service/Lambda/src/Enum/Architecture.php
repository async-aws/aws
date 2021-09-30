<?php

namespace AsyncAws\Lambda\Enum;

final class Architecture
{
    public const ARM_64 = 'arm64';
    public const X_86_64 = 'x86_64';

    public static function exists(string $value): bool
    {
        return isset([
            self::ARM_64 => true,
            self::X_86_64 => true,
        ][$value]);
    }
}
