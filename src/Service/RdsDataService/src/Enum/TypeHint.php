<?php

namespace AsyncAws\RdsDataService\Enum;

/**
 * A hint that specifies the correct object type for data type mapping.
 * **Values:**.
 *
 * - `DECIMAL` - The corresponding `String` parameter value is sent as an object of `DECIMAL` type to the database.
 * - `TIMESTAMP` - The corresponding `String` parameter value is sent as an object of `TIMESTAMP` type to the database.
 *   The accepted format is `YYYY-MM-DD HH:MM:SS[.FFF]`.
 * - `TIME` - The corresponding `String` parameter value is sent as an object of `TIME` type to the database. The
 *   accepted format is `HH:MM:SS[.FFF]`.
 * - `DATE` - The corresponding `String` parameter value is sent as an object of `DATE` type to the database. The
 *   accepted format is `YYYY-MM-DD`.
 */
final class TypeHint
{
    public const DATE = 'DATE';
    public const DECIMAL = 'DECIMAL';
    public const TIME = 'TIME';
    public const TIMESTAMP = 'TIMESTAMP';

    public static function exists(string $value): bool
    {
        return isset([
            self::DATE => true,
            self::DECIMAL => true,
            self::TIME => true,
            self::TIMESTAMP => true,
        ][$value]);
    }
}
