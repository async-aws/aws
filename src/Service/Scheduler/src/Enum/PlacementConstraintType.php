<?php

namespace AsyncAws\Scheduler\Enum;

final class PlacementConstraintType
{
    public const DISTINCT_INSTANCE = 'distinctInstance';
    public const MEMBER_OF = 'memberOf';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISTINCT_INSTANCE => true,
            self::MEMBER_OF => true,
        ][$value]);
    }
}
