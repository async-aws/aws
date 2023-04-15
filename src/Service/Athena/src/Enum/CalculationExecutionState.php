<?php

namespace AsyncAws\Athena\Enum;

/**
 * The state of the calculation execution. A description of each state follows.
 * `CREATING` - The calculation is in the process of being created.
 * `CREATED` - The calculation has been created and is ready to run.
 * `QUEUED` - The calculation has been queued for processing.
 * `RUNNING` - The calculation is running.
 * `CANCELING` - A request to cancel the calculation has been received and the system is working to stop it.
 * `CANCELED` - The calculation is no longer running as the result of a cancel request.
 * `COMPLETED` - The calculation has completed without error.
 * `FAILED` - The calculation failed and is no longer running.
 */
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
