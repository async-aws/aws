<?php

namespace AsyncAws\AppSync\Enum;

final class SchemaStatus
{
    public const ACTIVE = 'ACTIVE';
    public const DELETING = 'DELETING';
    public const FAILED = 'FAILED';
    public const NOT_APPLICABLE = 'NOT_APPLICABLE';
    public const PROCESSING = 'PROCESSING';
    public const SUCCESS = 'SUCCESS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::DELETING => true,
            self::FAILED => true,
            self::NOT_APPLICABLE => true,
            self::PROCESSING => true,
            self::SUCCESS => true,
        ][$value]);
    }
}
