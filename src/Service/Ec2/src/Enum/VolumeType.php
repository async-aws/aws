<?php

namespace AsyncAws\Ec2\Enum;

final class VolumeType
{
    public const GP_2 = 'gp2';
    public const GP_3 = 'gp3';
    public const IO_1 = 'io1';
    public const IO_2 = 'io2';
    public const SC_1 = 'sc1';
    public const STANDARD = 'standard';
    public const ST_1 = 'st1';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::GP_2 => true,
            self::GP_3 => true,
            self::IO_1 => true,
            self::IO_2 => true,
            self::SC_1 => true,
            self::STANDARD => true,
            self::ST_1 => true,
        ][$value]);
    }
}
