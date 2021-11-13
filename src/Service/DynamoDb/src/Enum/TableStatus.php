<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * The current state of the table:.
 *
 * - `CREATING` - The table is being created.
 * - `UPDATING` - The table is being updated.
 * - `DELETING` - The table is being deleted.
 * - `ACTIVE` - The table is ready for use.
 * - `INACCESSIBLE_ENCRYPTION_CREDENTIALS` - The KMS key used to encrypt the table in inaccessible. Table operations may
 *   fail due to failure to use the KMS key. DynamoDB will initiate the table archival process when a table's KMS key
 *   remains inaccessible for more than seven days.
 * - `ARCHIVING` - The table is being archived. Operations are not allowed until archival is complete.
 * - `ARCHIVED` - The table has been archived. See the ArchivalReason for more information.
 */
final class TableStatus
{
    public const ACTIVE = 'ACTIVE';
    public const ARCHIVED = 'ARCHIVED';
    public const ARCHIVING = 'ARCHIVING';
    public const CREATING = 'CREATING';
    public const DELETING = 'DELETING';
    public const INACCESSIBLE_ENCRYPTION_CREDENTIALS = 'INACCESSIBLE_ENCRYPTION_CREDENTIALS';
    public const UPDATING = 'UPDATING';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::ARCHIVED => true,
            self::ARCHIVING => true,
            self::CREATING => true,
            self::DELETING => true,
            self::INACCESSIBLE_ENCRYPTION_CREDENTIALS => true,
            self::UPDATING => true,
        ][$value]);
    }
}
