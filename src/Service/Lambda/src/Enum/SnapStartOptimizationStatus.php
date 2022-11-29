<?php

namespace AsyncAws\Lambda\Enum;

/**
 * When you provide a qualified Amazon Resource Name (ARN), this response element indicates whether SnapStart is
 * activated for the specified function version.
 *
 * @see https://docs.aws.amazon.com/lambda/latest/dg/configuration-versions.html#versioning-versions-using
 */
final class SnapStartOptimizationStatus
{
    public const OFF = 'Off';
    public const ON = 'On';

    public static function exists(string $value): bool
    {
        return isset([
            self::OFF => true,
            self::ON => true,
        ][$value]);
    }
}
