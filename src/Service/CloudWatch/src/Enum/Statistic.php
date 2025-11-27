<?php

namespace AsyncAws\CloudWatch\Enum;

final class Statistic
{
    public const AVERAGE = 'Average';
    public const MAXIMUM = 'Maximum';
    public const MINIMUM = 'Minimum';
    public const SAMPLE_COUNT = 'SampleCount';
    public const SUM = 'Sum';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AVERAGE => true,
            self::MAXIMUM => true,
            self::MINIMUM => true,
            self::SAMPLE_COUNT => true,
            self::SUM => true,
        ][$value]);
    }
}
