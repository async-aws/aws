<?php

namespace AsyncAws\Lambda\Enum;

final class Architecture
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const ARM64 = 'arm64';
    public const X86_64 = 'x86_64';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ARM64 => true,
            self::X86_64 => true,
        ][$value]);
    }
}
