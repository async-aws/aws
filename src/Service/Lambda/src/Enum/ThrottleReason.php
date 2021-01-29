<?php

namespace AsyncAws\Lambda\Enum;

final class ThrottleReason
{
    public const CALLER_RATE_LIMIT_EXCEEDED = 'CallerRateLimitExceeded';
    public const CONCURRENT_INVOCATION_LIMIT_EXCEEDED = 'ConcurrentInvocationLimitExceeded';
    public const FUNCTION_INVOCATION_RATE_LIMIT_EXCEEDED = 'FunctionInvocationRateLimitExceeded';
    public const RESERVED_FUNCTION_CONCURRENT_INVOCATION_LIMIT_EXCEEDED = 'ReservedFunctionConcurrentInvocationLimitExceeded';
    public const RESERVED_FUNCTION_INVOCATION_RATE_LIMIT_EXCEEDED = 'ReservedFunctionInvocationRateLimitExceeded';

    public static function exists(string $value): bool
    {
        return isset([
            self::CALLER_RATE_LIMIT_EXCEEDED => true,
            self::CONCURRENT_INVOCATION_LIMIT_EXCEEDED => true,
            self::FUNCTION_INVOCATION_RATE_LIMIT_EXCEEDED => true,
            self::RESERVED_FUNCTION_CONCURRENT_INVOCATION_LIMIT_EXCEEDED => true,
            self::RESERVED_FUNCTION_INVOCATION_RATE_LIMIT_EXCEEDED => true,
        ][$value]);
    }
}
