<?php

namespace AsyncAws\Athena\Enum;

final class StatementType
{
    public const DDL = 'DDL';
    public const DML = 'DML';
    public const UTILITY = 'UTILITY';

    public static function exists(string $value): bool
    {
        return isset([
            self::DDL => true,
            self::DML => true,
            self::UTILITY => true,
        ][$value]);
    }
}
