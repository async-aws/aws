<?php

namespace AsyncAws\CloudFormation\Enum;

/**
 * Status of the stack's actual configuration compared to its expected template configuration.
 *
 * - `DRIFTED`: The stack differs from its expected template configuration. A stack is considered to have drifted if one
 *   or more of its resources have drifted.
 * - `NOT_CHECKED`: AWS CloudFormation has not checked if the stack differs from its expected template configuration.
 * - `IN_SYNC`: The stack's actual configuration matches its expected template configuration.
 * - `UNKNOWN`: This value is reserved for future use.
 */
final class StackDriftStatus
{
    public const DRIFTED = 'DRIFTED';
    public const IN_SYNC = 'IN_SYNC';
    public const NOT_CHECKED = 'NOT_CHECKED';
    public const UNKNOWN = 'UNKNOWN';

    public static function exists(string $value): bool
    {
        return isset([
            self::DRIFTED => true,
            self::IN_SYNC => true,
            self::NOT_CHECKED => true,
            self::UNKNOWN => true,
        ][$value]);
    }
}
