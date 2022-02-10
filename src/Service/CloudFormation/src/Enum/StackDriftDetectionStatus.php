<?php

namespace AsyncAws\CloudFormation\Enum;

/**
 * The status of the stack drift detection operation.
 *
 * - `DETECTION_COMPLETE`: The stack drift detection operation has successfully completed for all resources in the stack
 *   that support drift detection. (Resources that don't currently support stack detection remain unchecked.)
 *   If you specified logical resource IDs for CloudFormation to use as a filter for the stack drift detection
 *   operation, only the resources with those logical IDs are checked for drift.
 * - `DETECTION_FAILED`: The stack drift detection operation has failed for at least one resource in the stack. Results
 *   will be available for resources on which CloudFormation successfully completed drift detection.
 * - `DETECTION_IN_PROGRESS`: The stack drift detection operation is currently in progress.
 */
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
