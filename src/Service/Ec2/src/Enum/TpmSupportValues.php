<?php

namespace AsyncAws\Ec2\Enum;

final class TpmSupportValues
{
    public const V_2_0 = 'v2.0';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::V_2_0 => true,
        ][$value]);
    }
}
