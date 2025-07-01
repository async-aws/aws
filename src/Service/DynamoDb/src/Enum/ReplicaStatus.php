<?php

namespace AsyncAws\DynamoDb\Enum;

final class ReplicaStatus
{
    public const ACTIVE = 'ACTIVE';
    public const ARCHIVED = 'ARCHIVED';
    public const ARCHIVING = 'ARCHIVING';
    public const CREATING = 'CREATING';
    public const CREATION_FAILED = 'CREATION_FAILED';
    public const DELETING = 'DELETING';
    public const INACCESSIBLE_ENCRYPTION_CREDENTIALS = 'INACCESSIBLE_ENCRYPTION_CREDENTIALS';
    public const REGION_DISABLED = 'REGION_DISABLED';
    public const REPLICATION_NOT_AUTHORIZED = 'REPLICATION_NOT_AUTHORIZED';
    public const UPDATING = 'UPDATING';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::ARCHIVED => true,
            self::ARCHIVING => true,
            self::CREATING => true,
            self::CREATION_FAILED => true,
            self::DELETING => true,
            self::INACCESSIBLE_ENCRYPTION_CREDENTIALS => true,
            self::REGION_DISABLED => true,
            self::REPLICATION_NOT_AUTHORIZED => true,
            self::UPDATING => true,
        ][$value]);
    }
}
