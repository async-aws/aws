<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * The table class of the new table. Valid values are `STANDARD` and `STANDARD_INFREQUENT_ACCESS`.
 */
final class TableClass
{
    public const STANDARD = 'STANDARD';
    public const STANDARD_INFREQUENT_ACCESS = 'STANDARD_INFREQUENT_ACCESS';

    public static function exists(string $value): bool
    {
        return isset([
            self::STANDARD => true,
            self::STANDARD_INFREQUENT_ACCESS => true,
        ][$value]);
    }
}
