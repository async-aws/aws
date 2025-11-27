<?php

namespace AsyncAws\Athena\Enum;

/**
 * The reason for the query throttling, for example, when it exceeds the concurrent query limit.
 */
final class ThrottleReason
{
    public const CONCURRENT_QUERY_LIMIT_EXCEEDED = 'CONCURRENT_QUERY_LIMIT_EXCEEDED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CONCURRENT_QUERY_LIMIT_EXCEEDED => true,
        ][$value]);
    }
}
