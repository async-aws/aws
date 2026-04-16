<?php

namespace AsyncAws\Ec2\Enum;

final class HypervisorType
{
    public const OVM = 'ovm';
    public const XEN = 'xen';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::OVM => true,
            self::XEN => true,
        ][$value]);
    }
}
