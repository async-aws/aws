<?php

namespace AsyncAws\SecretsManager\Enum;

final class StatusType
{
    public const FAILED = 'Failed';
    public const IN_PROGRESS = 'InProgress';
    public const IN_SYNC = 'InSync';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FAILED => true,
            self::IN_PROGRESS => true,
            self::IN_SYNC => true,
        ][$value]);
    }
}
