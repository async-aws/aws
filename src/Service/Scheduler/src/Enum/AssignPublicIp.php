<?php

namespace AsyncAws\Scheduler\Enum;

/**
 * Specifies whether the task's elastic network interface receives a public IP address. You can specify `ENABLED` only
 * when `LaunchType` in `EcsParameters` is set to `FARGATE`.
 */
final class AssignPublicIp
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
