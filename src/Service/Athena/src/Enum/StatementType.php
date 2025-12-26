<?php

namespace AsyncAws\Athena\Enum;

final class StatementType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const DDL = 'DDL';
    public const DML = 'DML';
    public const UTILITY = 'UTILITY';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DDL => true,
            self::DML => true,
            self::UTILITY => true,
        ][$value]);
    }
}
