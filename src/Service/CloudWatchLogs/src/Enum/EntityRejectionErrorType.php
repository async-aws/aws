<?php

namespace AsyncAws\CloudWatchLogs\Enum;

final class EntityRejectionErrorType
{
    public const ENTITY_SIZE_TOO_LARGE = 'EntitySizeTooLarge';
    public const INVALID_ATTRIBUTES = 'InvalidAttributes';
    public const INVALID_ENTITY = 'InvalidEntity';
    public const INVALID_KEY_ATTRIBUTES = 'InvalidKeyAttributes';
    public const INVALID_TYPE_VALUE = 'InvalidTypeValue';
    public const MISSING_REQUIRED_FIELDS = 'MissingRequiredFields';
    public const UNSUPPORTED_LOG_GROUP_TYPE = 'UnsupportedLogGroupType';

    public static function exists(string $value): bool
    {
        return isset([
            self::ENTITY_SIZE_TOO_LARGE => true,
            self::INVALID_ATTRIBUTES => true,
            self::INVALID_ENTITY => true,
            self::INVALID_KEY_ATTRIBUTES => true,
            self::INVALID_TYPE_VALUE => true,
            self::MISSING_REQUIRED_FIELDS => true,
            self::UNSUPPORTED_LOG_GROUP_TYPE => true,
        ][$value]);
    }
}
