<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * The current state of the replica:.
 *
 * - `CREATING` - The replica is being created.
 * - `UPDATING` - The replica is being updated.
 * - `DELETING` - The replica is being deleted.
 * - `ACTIVE` - The replica is ready for use.
 * - `REGION_DISABLED` - The replica is inaccessible because the Amazon Web Services Region has been disabled.
 *
 *   > If the Amazon Web Services Region remains inaccessible for more than 20 hours, DynamoDB will remove this replica
 *   > from the replication group. The replica will not be deleted and replication will stop from and to this region.
 *
 * - `INACCESSIBLE_ENCRYPTION_CREDENTIALS ` - The KMS key used to encrypt the table is inaccessible.
 *
 *   > If the KMS key remains inaccessible for more than 20 hours, DynamoDB will remove this replica from the
 *   > replication group. The replica will not be deleted and replication will stop from and to this region.
 */
final class ReplicaStatus
{
    public const ACTIVE = 'ACTIVE';
    public const CREATING = 'CREATING';
    public const CREATION_FAILED = 'CREATION_FAILED';
    public const DELETING = 'DELETING';
    public const INACCESSIBLE_ENCRYPTION_CREDENTIALS = 'INACCESSIBLE_ENCRYPTION_CREDENTIALS';
    public const REGION_DISABLED = 'REGION_DISABLED';
    public const UPDATING = 'UPDATING';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::CREATING => true,
            self::CREATION_FAILED => true,
            self::DELETING => true,
            self::INACCESSIBLE_ENCRYPTION_CREDENTIALS => true,
            self::REGION_DISABLED => true,
            self::UPDATING => true,
        ][$value]);
    }
}
