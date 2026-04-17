<?php

namespace AsyncAws\Ec2\Enum;

final class VirtualizationType
{
    public const HVM = 'hvm';
    public const PARAVIRTUAL = 'paravirtual';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HVM => true,
            self::PARAVIRTUAL => true,
        ][$value]);
    }
}
