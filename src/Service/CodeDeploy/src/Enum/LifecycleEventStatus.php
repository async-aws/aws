<?php

namespace AsyncAws\CodeDeploy\Enum;

final class LifecycleEventStatus
{
    public const FAILED = 'Failed';
    public const IN_PROGRESS = 'InProgress';
    public const PENDING = 'Pending';
    public const SKIPPED = 'Skipped';
    public const SUCCEEDED = 'Succeeded';
    public const UNKNOWN = 'Unknown';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FAILED => true,
            self::IN_PROGRESS => true,
            self::PENDING => true,
            self::SKIPPED => true,
            self::SUCCEEDED => true,
            self::UNKNOWN => true,
        ][$value]);
    }
}
