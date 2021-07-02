<?php

namespace AsyncAws\SecretsManager\Enum;

/**
 * The status can be `InProgress`, `Failed`, or `InSync`.
 */
final class StatusType
{
    public const FAILED = 'Failed';
    public const IN_PROGRESS = 'InProgress';
    public const IN_SYNC = 'InSync';

    public static function exists(string $value): bool
    {
        return isset([
            self::FAILED => true,
            self::IN_PROGRESS => true,
            self::IN_SYNC => true,
        ][$value]);
    }
}
