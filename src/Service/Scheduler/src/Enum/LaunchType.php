<?php

namespace AsyncAws\Scheduler\Enum;

final class LaunchType
{
    public const EC2 = 'EC2';
    public const EXTERNAL = 'EXTERNAL';
    public const FARGATE = 'FARGATE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EC2 => true,
            self::EXTERNAL => true,
            self::FARGATE => true,
        ][$value]);
    }
}
