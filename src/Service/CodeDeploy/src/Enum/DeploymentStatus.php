<?php

namespace AsyncAws\CodeDeploy\Enum;

final class DeploymentStatus
{
    public const BAKING = 'Baking';
    public const CREATED = 'Created';
    public const FAILED = 'Failed';
    public const IN_PROGRESS = 'InProgress';
    public const QUEUED = 'Queued';
    public const READY = 'Ready';
    public const STOPPED = 'Stopped';
    public const SUCCEEDED = 'Succeeded';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BAKING => true,
            self::CREATED => true,
            self::FAILED => true,
            self::IN_PROGRESS => true,
            self::QUEUED => true,
            self::READY => true,
            self::STOPPED => true,
            self::SUCCEEDED => true,
        ][$value]);
    }
}
