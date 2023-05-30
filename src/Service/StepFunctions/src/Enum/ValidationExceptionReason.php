<?php

namespace AsyncAws\StepFunctions\Enum;

final class ValidationExceptionReason
{
    public const API_DOES_NOT_SUPPORT_LABELED_ARNS = 'API_DOES_NOT_SUPPORT_LABELED_ARNS';
    public const CANNOT_UPDATE_COMPLETED_MAP_RUN = 'CANNOT_UPDATE_COMPLETED_MAP_RUN';
    public const MISSING_REQUIRED_PARAMETER = 'MISSING_REQUIRED_PARAMETER';

    public static function exists(string $value): bool
    {
        return isset([
            self::API_DOES_NOT_SUPPORT_LABELED_ARNS => true,
            self::CANNOT_UPDATE_COMPLETED_MAP_RUN => true,
            self::MISSING_REQUIRED_PARAMETER => true,
        ][$value]);
    }
}
