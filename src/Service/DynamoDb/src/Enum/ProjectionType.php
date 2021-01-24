<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * The set of attributes that are projected into the index:.
 *
 * - `KEYS_ONLY` - Only the index and primary keys are projected into the index.
 * - `INCLUDE` - In addition to the attributes described in `KEYS_ONLY`, the secondary index will include other non-key
 *   attributes that you specify.
 * - `ALL` - All of the table attributes are projected into the index.
 */
final class ProjectionType
{
    public const ALL = 'ALL';
    public const INCLUDE = 'INCLUDE';
    public const KEYS_ONLY = 'KEYS_ONLY';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL => true,
            self::INCLUDE => true,
            self::KEYS_ONLY => true,
        ][$value]);
    }
}
