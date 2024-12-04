<?php

namespace AsyncAws\DynamoDb\Enum;

final class MultiRegionConsistency
{
    public const EVENTUAL = 'EVENTUAL';
    public const STRONG = 'STRONG';

    public static function exists(string $value): bool
    {
        return isset([
            self::EVENTUAL => true,
            self::STRONG => true,
        ][$value]);
    }
}
