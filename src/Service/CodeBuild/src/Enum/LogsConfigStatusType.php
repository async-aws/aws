<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The current status of the logs in CloudWatch Logs for a build project. Valid values are:.
 *
 * - `ENABLED`: CloudWatch Logs are enabled for this build project.
 * - `DISABLED`: CloudWatch Logs are not enabled for this build project.
 */
final class LogsConfigStatusType
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
