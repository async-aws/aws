<?php

namespace AsyncAws\Lambda\Enum;

final class Architecture
{
    public const ARM64 = 'arm64';
    public const X86_64 = 'x86_64';

    public static function exists(string $value): bool
    {
        return isset([
            self::ARM64 => true,
            self::X86_64 => true,
        ][$value]);
    }
}
