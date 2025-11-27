<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The tag type that AWS Billing and Cost Management will use to sort your AWS Elemental MediaConvert costs on any
 * billing report that you set up.
 */
final class BillingTagsSource
{
    public const JOB = 'JOB';
    public const JOB_TEMPLATE = 'JOB_TEMPLATE';
    public const PRESET = 'PRESET';
    public const QUEUE = 'QUEUE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::JOB => true,
            self::JOB_TEMPLATE => true,
            self::PRESET => true,
            self::QUEUE => true,
        ][$value]);
    }
}
