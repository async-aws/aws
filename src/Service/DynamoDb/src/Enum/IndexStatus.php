<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * The current state of the global secondary index:.
 *
 * - `CREATING` - The index is being created.
 * - `UPDATING` - The index is being updated.
 * - `DELETING` - The index is being deleted.
 * - `ACTIVE` - The index is ready for use.
 */
final class IndexStatus
{
    public const ACTIVE = 'ACTIVE';
    public const CREATING = 'CREATING';
    public const DELETING = 'DELETING';
    public const UPDATING = 'UPDATING';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::CREATING => true,
            self::DELETING => true,
            self::UPDATING => true,
        ][$value]);
    }
}
