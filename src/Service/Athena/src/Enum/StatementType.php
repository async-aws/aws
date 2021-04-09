<?php

namespace AsyncAws\Athena\Enum;

/**
 * The type of query statement that was run. `DDL` indicates DDL query statements. `DML` indicates DML (Data
 * Manipulation Language) query statements, such as `CREATE TABLE AS SELECT`. `UTILITY` indicates query statements other
 * than DDL and DML, such as `SHOW CREATE TABLE`, or `DESCRIBE &lt;table&gt;`.
 */
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
