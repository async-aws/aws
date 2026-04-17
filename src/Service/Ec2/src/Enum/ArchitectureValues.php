<?php

namespace AsyncAws\Ec2\Enum;

final class ArchitectureValues
{
    public const ARM64 = 'arm64';
    public const ARM64_MAC = 'arm64_mac';
    public const I_386 = 'i386';
    public const X86_64 = 'x86_64';
    public const X86_64_MAC = 'x86_64_mac';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ARM64 => true,
            self::ARM64_MAC => true,
            self::I_386 => true,
            self::X86_64 => true,
            self::X86_64_MAC => true,
        ][$value]);
    }
}
