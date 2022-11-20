<?php

namespace AsyncAws\CodeCommit\Enum;

/**
 * The order in which to sort the results of a list repositories operation.
 */
final class OrderEnum
{
    public const ASCENDING = 'ascending';
    public const DESCENDING = 'descending';

    public static function exists(string $value): bool
    {
        return isset([
            self::ASCENDING => true,
            self::DESCENDING => true,
        ][$value]);
    }
}
