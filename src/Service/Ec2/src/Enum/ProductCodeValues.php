<?php

namespace AsyncAws\Ec2\Enum;

final class ProductCodeValues
{
    public const DEVPAY = 'devpay';
    public const MARKETPLACE = 'marketplace';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEVPAY => true,
            self::MARKETPLACE => true,
        ][$value]);
    }
}
