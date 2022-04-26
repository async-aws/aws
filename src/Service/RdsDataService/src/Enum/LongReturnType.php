<?php

namespace AsyncAws\RdsDataService\Enum;

/**
 * A value that indicates how a field of `LONG` type is represented. Allowed values are `LONG` and `STRING`. The default
 * is `LONG`. Specify `STRING` if the length or precision of numeric values might cause truncation or rounding errors.
 */
final class LongReturnType
{
    public const LONG = 'LONG';
    public const STRING = 'STRING';

    public static function exists(string $value): bool
    {
        return isset([
            self::LONG => true,
            self::STRING => true,
        ][$value]);
    }
}
