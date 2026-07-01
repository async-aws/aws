<?php

namespace AsyncAws\CodeBuild\Enum;

final class HostKernel
{
    public const LINUX_KERNEL_4 = 'LINUX_KERNEL_4';
    public const LINUX_KERNEL_6 = 'LINUX_KERNEL_6';
    public const LINUX_KERNEL_LATEST = 'LINUX_KERNEL_LATEST';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::LINUX_KERNEL_4 => true,
            self::LINUX_KERNEL_6 => true,
            self::LINUX_KERNEL_LATEST => true,
        ][$value]);
    }
}
