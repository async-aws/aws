<?php

namespace AsyncAws\Athena\Enum;

final class StatementType
{
    public const DDL = 'DDL';
    public const DML = 'DML';
    public const UTILITY = 'UTILITY';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

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
