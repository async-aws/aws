<?php

namespace AsyncAws\RdsDataService\Enum;

/**
 * A value that indicates how a field of `DECIMAL` type is represented in the response. The value of `STRING`, the
 * default, specifies that it is converted to a String value. The value of `DOUBLE_OR_LONG` specifies that it is
 * converted to a Long value if its scale is 0, or to a Double value otherwise.
 *
 * ! Conversion to Double or Long can result in roundoff errors due to precision loss. We recommend converting to
 * ! String, especially when working with currency values.
 */
final class DecimalReturnType
{
    public const DOUBLE_OR_LONG = 'DOUBLE_OR_LONG';
    public const STRING = 'STRING';

    public static function exists(string $value): bool
    {
        return isset([
            self::DOUBLE_OR_LONG => true,
            self::STRING => true,
        ][$value]);
    }
}
