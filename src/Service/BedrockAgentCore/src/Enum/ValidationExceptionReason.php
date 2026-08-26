<?php

namespace AsyncAws\BedrockAgentCore\Enum;

final class ValidationExceptionReason
{
    public const CANNOT_PARSE = 'CannotParse';
    public const EVENT_IN_OTHER_SESSION = 'EventInOtherSession';
    public const FIELD_VALIDATION_FAILED = 'FieldValidationFailed';
    public const IDEMPOTENT_PARAMETER_MISMATCH_EXCEPTION = 'IdempotentParameterMismatchException';
    public const RESOURCE_CONFLICT = 'ResourceConflict';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CANNOT_PARSE => true,
            self::EVENT_IN_OTHER_SESSION => true,
            self::FIELD_VALIDATION_FAILED => true,
            self::IDEMPOTENT_PARAMETER_MISMATCH_EXCEPTION => true,
            self::RESOURCE_CONFLICT => true,
        ][$value]);
    }
}
