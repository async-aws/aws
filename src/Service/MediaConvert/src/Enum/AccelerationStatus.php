<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Describes whether the current job is running with accelerated transcoding. For jobs that have Acceleration
 * (AccelerationMode) set to DISABLED, AccelerationStatus is always NOT_APPLICABLE. For jobs that have Acceleration
 * (AccelerationMode) set to ENABLED or PREFERRED, AccelerationStatus is one of the other states. AccelerationStatus is
 * IN_PROGRESS initially, while the service determines whether the input files and job settings are compatible with
 * accelerated transcoding. If they are, AcclerationStatus is ACCELERATED. If your input files and job settings aren't
 * compatible with accelerated transcoding, the service either fails your job or runs it without accelerated
 * transcoding, depending on how you set Acceleration (AccelerationMode). When the service runs your job without
 * accelerated transcoding, AccelerationStatus is NOT_ACCELERATED.
 */
final class AccelerationStatus
{
    public const ACCELERATED = 'ACCELERATED';
    public const IN_PROGRESS = 'IN_PROGRESS';
    public const NOT_ACCELERATED = 'NOT_ACCELERATED';
    public const NOT_APPLICABLE = 'NOT_APPLICABLE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ACCELERATED => true,
            self::IN_PROGRESS => true,
            self::NOT_ACCELERATED => true,
            self::NOT_APPLICABLE => true,
        ][$value]);
    }
}
