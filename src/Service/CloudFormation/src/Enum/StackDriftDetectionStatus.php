<?php

namespace AsyncAws\CloudFormation\Enum;

final class StackDriftDetectionStatus
{
    public const DETECTION_COMPLETE = 'DETECTION_COMPLETE';
    public const DETECTION_FAILED = 'DETECTION_FAILED';
    public const DETECTION_IN_PROGRESS = 'DETECTION_IN_PROGRESS';

    public static function exists(string $value): bool
    {
        return isset([
            self::DETECTION_COMPLETE => true,
            self::DETECTION_FAILED => true,
            self::DETECTION_IN_PROGRESS => true,
        ][$value]);
    }
}
