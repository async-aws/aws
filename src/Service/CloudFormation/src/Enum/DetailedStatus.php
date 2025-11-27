<?php

namespace AsyncAws\CloudFormation\Enum;

final class DetailedStatus
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const CONFIGURATION_COMPLETE = 'CONFIGURATION_COMPLETE';
    public const VALIDATION_FAILED = 'VALIDATION_FAILED';

    public static function exists(string $value): bool
    {
        return isset([
            self::CONFIGURATION_COMPLETE => true,
            self::VALIDATION_FAILED => true,
        ][$value]);
    }
}
