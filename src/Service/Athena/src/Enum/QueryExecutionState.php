<?php

namespace AsyncAws\Athena\Enum;

final class QueryExecutionState
{
    public const CANCELLED = 'CANCELLED';
    public const FAILED = 'FAILED';
    public const QUEUED = 'QUEUED';
    public const RUNNING = 'RUNNING';
    public const SUCCEEDED = 'SUCCEEDED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CANCELLED => true,
            self::FAILED => true,
            self::QUEUED => true,
            self::RUNNING => true,
            self::SUCCEEDED => true,
        ][$value]);
    }
}
