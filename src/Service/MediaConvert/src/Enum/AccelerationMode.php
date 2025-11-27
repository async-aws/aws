<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether the service runs your job with accelerated transcoding. Choose DISABLED if you don't want accelerated
 * transcoding. Choose ENABLED if you want your job to run with accelerated transcoding and to fail if your input files
 * or your job settings aren't compatible with accelerated transcoding. Choose PREFERRED if you want your job to run
 * with accelerated transcoding if the job is compatible with the feature and to run at standard speed if it's not.
 */
final class AccelerationMode
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const PREFERRED = 'PREFERRED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
            self::PREFERRED => true,
        ][$value]);
    }
}
