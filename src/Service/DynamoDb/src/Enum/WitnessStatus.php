<?php

namespace AsyncAws\DynamoDb\Enum;

final class WitnessStatus
{
    public const ACTIVE = 'ACTIVE';
    public const CREATING = 'CREATING';
    public const DELETING = 'DELETING';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::CREATING => true,
            self::DELETING => true,
        ][$value]);
    }
}
