<?php

namespace AsyncAws\Athena\Enum;

final class ThrottleReason
{
    public const CONCURRENT_QUERY_LIMIT_EXCEEDED = 'CONCURRENT_QUERY_LIMIT_EXCEEDED';

    public static function exists(string $value): bool
    {
        return isset([
            self::CONCURRENT_QUERY_LIMIT_EXCEEDED => true,
        ][$value]);
    }
}
