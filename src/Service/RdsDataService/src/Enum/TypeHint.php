<?php

namespace AsyncAws\RdsDataService\Enum;

/**
 * A hint that specifies the correct object type for data type mapping. Possible values are as follows:.
 *
 * - `DATE` - The corresponding `String` parameter value is sent as an object of `DATE` type to the database. The
 *   accepted format is `YYYY-MM-DD`.
 * - `DECIMAL` - The corresponding `String` parameter value is sent as an object of `DECIMAL` type to the database.
 * - `JSON` - The corresponding `String` parameter value is sent as an object of `JSON` type to the database.
 * - `TIME` - The corresponding `String` parameter value is sent as an object of `TIME` type to the database. The
 *   accepted format is `HH:MM:SS[.FFF]`.
 * - `TIMESTAMP` - The corresponding `String` parameter value is sent as an object of `TIMESTAMP` type to the database.
 *   The accepted format is `YYYY-MM-DD HH:MM:SS[.FFF]`.
 * - `UUID` - The corresponding `String` parameter value is sent as an object of `UUID` type to the database.
 */
final class TypeHint
{
    public const DATE = 'DATE';
    public const DECIMAL = 'DECIMAL';
    public const JSON = 'JSON';
    public const TIME = 'TIME';
    public const TIMESTAMP = 'TIMESTAMP';
    public const UUID = 'UUID';

    public static function exists(string $value): bool
    {
        return isset([
            self::DATE => true,
            self::DECIMAL => true,
            self::JSON => true,
            self::TIME => true,
            self::TIMESTAMP => true,
            self::UUID => true,
        ][$value]);
    }
}
