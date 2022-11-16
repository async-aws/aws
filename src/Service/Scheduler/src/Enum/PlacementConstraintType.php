<?php

namespace AsyncAws\Scheduler\Enum;

/**
 * The type of constraint. Use `distinctInstance` to ensure that each task in a particular group is running on a
 * different container instance. Use `memberOf` to restrict the selection to a group of valid candidates.
 */
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
