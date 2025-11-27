<?php

namespace AsyncAws\CodeBuild\Enum;

final class StatusType
{
    public const FAILED = 'FAILED';
    public const FAULT = 'FAULT';
    public const IN_PROGRESS = 'IN_PROGRESS';
    public const STOPPED = 'STOPPED';
    public const SUCCEEDED = 'SUCCEEDED';
    public const TIMED_OUT = 'TIMED_OUT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
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
