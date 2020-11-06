<?php

namespace AsyncAws\DynamoDb\Enum;

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
