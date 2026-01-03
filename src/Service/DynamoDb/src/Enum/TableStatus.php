<?php

namespace AsyncAws\DynamoDb\Enum;

final class TableStatus
{
    public const ACTIVE = 'ACTIVE';
    public const ARCHIVED = 'ARCHIVED';
    public const ARCHIVING = 'ARCHIVING';
    public const CREATING = 'CREATING';
    public const DELETING = 'DELETING';
    public const INACCESSIBLE_ENCRYPTION_CREDENTIALS = 'INACCESSIBLE_ENCRYPTION_CREDENTIALS';
    public const REPLICATION_NOT_AUTHORIZED = 'REPLICATION_NOT_AUTHORIZED';
    public const UPDATING = 'UPDATING';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::ARCHIVED => true,
            self::ARCHIVING => true,
            self::CREATING => true,
            self::DELETING => true,
            self::INACCESSIBLE_ENCRYPTION_CREDENTIALS => true,
            self::REPLICATION_NOT_AUTHORIZED => true,
            self::UPDATING => true,
        ][$value]);
    }
}
