<?php

namespace AsyncAws\Athena\Enum;

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
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
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
