<?php

namespace AsyncAws\Athena\Enum;

final class CalculationExecutionState
{
    public const CANCELED = 'CANCELED';
    public const CANCELING = 'CANCELING';
    public const COMPLETED = 'COMPLETED';
    public const CREATED = 'CREATED';
    public const CREATING = 'CREATING';
    public const FAILED = 'FAILED';
    public const QUEUED = 'QUEUED';
    public const RUNNING = 'RUNNING';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CANCELED => true,
            self::CANCELING => true,
            self::COMPLETED => true,
            self::CREATED => true,
            self::CREATING => true,
            self::FAILED => true,
            self::QUEUED => true,
            self::RUNNING => true,
        ][$value]);
    }
}
