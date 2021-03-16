<?php

namespace AsyncAws\CodeDeploy\Enum;

/**
 * The result of a Lambda function that validates a deployment lifecycle event. `Succeeded` and `Failed` are the only
 * valid values for `status`.
 */
final class LifecycleEventStatus
{
    public const FAILED = 'Failed';
    public const IN_PROGRESS = 'InProgress';
    public const PENDING = 'Pending';
    public const SKIPPED = 'Skipped';
    public const SUCCEEDED = 'Succeeded';
    public const UNKNOWN = 'Unknown';

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
