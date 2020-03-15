<?php

namespace AsyncAws\DynamoDb\Enum;

class TableStatus
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
