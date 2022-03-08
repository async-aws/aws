<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The current status of the build phase. Valid values include:.
 *
 * - `FAILED`: The build phase failed.
 * - `FAULT`: The build phase faulted.
 * - `IN_PROGRESS`: The build phase is still in progress.
 * - `STOPPED`: The build phase stopped.
 * - `SUCCEEDED`: The build phase succeeded.
 * - `TIMED_OUT`: The build phase timed out.
 */
final class StatusType
{
    public const FAILED = 'FAILED';
    public const FAULT = 'FAULT';
    public const IN_PROGRESS = 'IN_PROGRESS';
    public const STOPPED = 'STOPPED';
    public const SUCCEEDED = 'SUCCEEDED';
    public const TIMED_OUT = 'TIMED_OUT';

    public static function exists(string $value): bool
    {
        return isset([
            self::FAILED => true,
            self::FAULT => true,
            self::IN_PROGRESS => true,
            self::STOPPED => true,
            self::SUCCEEDED => true,
            self::TIMED_OUT => true,
        ][$value]);
    }
}
