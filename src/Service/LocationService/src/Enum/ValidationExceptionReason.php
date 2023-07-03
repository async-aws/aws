<?php

namespace AsyncAws\LocationService\Enum;

final class ValidationExceptionReason
{
    public const CANNOT_PARSE = 'CannotParse';
    public const FIELD_VALIDATION_FAILED = 'FieldValidationFailed';
    public const MISSING = 'Missing';
    public const OTHER = 'Other';
    public const UNKNOWN_OPERATION = 'UnknownOperation';

    public static function exists(string $value): bool
    {
        return isset([
            self::CANNOT_PARSE => true,
            self::FIELD_VALIDATION_FAILED => true,
            self::MISSING => true,
            self::OTHER => true,
            self::UNKNOWN_OPERATION => true,
        ][$value]);
    }
}
