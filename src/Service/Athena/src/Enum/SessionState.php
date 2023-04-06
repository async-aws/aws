<?php

namespace AsyncAws\Athena\Enum;

/**
 * The state of the session. A description of each state follows.
 * `CREATING` - The session is being started, including acquiring resources.
 * `CREATED` - The session has been started.
 * `IDLE` - The session is able to accept a calculation.
 * `BUSY` - The session is processing another task and is unable to accept a calculation.
 * `TERMINATING` - The session is in the process of shutting down.
 * `TERMINATED` - The session and its resources are no longer running.
 * `DEGRADED` - The session has no healthy coordinators.
 * `FAILED` - Due to a failure, the session and its resources are no longer running.
 */
final class SessionState
{
    public const BUSY = 'BUSY';
    public const CREATED = 'CREATED';
    public const CREATING = 'CREATING';
    public const DEGRADED = 'DEGRADED';
    public const FAILED = 'FAILED';
    public const IDLE = 'IDLE';
    public const TERMINATED = 'TERMINATED';
    public const TERMINATING = 'TERMINATING';

    public static function exists(string $value): bool
    {
        return isset([
            self::BUSY => true,
            self::CREATED => true,
            self::CREATING => true,
            self::DEGRADED => true,
            self::FAILED => true,
            self::IDLE => true,
            self::TERMINATED => true,
            self::TERMINATING => true,
        ][$value]);
    }
}
