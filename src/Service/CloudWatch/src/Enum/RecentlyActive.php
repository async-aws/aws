<?php

namespace AsyncAws\CloudWatch\Enum;

/**
 * To filter the results to show only metrics that have had data points published in the past three hours, specify this
 * parameter with a value of `PT3H`. This is the only valid value for this parameter.
 * The results that are returned are an approximation of the value you specify. There is a low probability that the
 * returned results include metrics with last published data as much as 40 minutes more than the specified time
 * interval.
 */
final class RecentlyActive
{
    public const PT3H = 'PT3H';

    public static function exists(string $value): bool
    {
        return isset([
            self::PT3H => true,
        ][$value]);
    }
}
