<?php

namespace AsyncAws\AppSync\Enum;

/**
 * The current state of the schema (PROCESSING, FAILED, SUCCESS, or NOT_APPLICABLE). When the schema is in the ACTIVE
 * state, you can add data.
 */
final class SchemaStatus
{
    public const ACTIVE = 'ACTIVE';
    public const DELETING = 'DELETING';
    public const FAILED = 'FAILED';
    public const NOT_APPLICABLE = 'NOT_APPLICABLE';
    public const PROCESSING = 'PROCESSING';
    public const SUCCESS = 'SUCCESS';

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
