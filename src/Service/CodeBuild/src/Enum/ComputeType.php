<?php

namespace AsyncAws\CodeBuild\Enum;

final class ComputeType
{
    public const BUILD_GENERAL1_2XLARGE = 'BUILD_GENERAL1_2XLARGE';
    public const BUILD_GENERAL1_LARGE = 'BUILD_GENERAL1_LARGE';
    public const BUILD_GENERAL1_MEDIUM = 'BUILD_GENERAL1_MEDIUM';
    public const BUILD_GENERAL1_SMALL = 'BUILD_GENERAL1_SMALL';

    public static function exists(string $value): bool
    {
        return isset([
            self::BUILD_GENERAL1_2XLARGE => true,
            self::BUILD_GENERAL1_LARGE => true,
            self::BUILD_GENERAL1_MEDIUM => true,
            self::BUILD_GENERAL1_SMALL => true,
        ][$value]);
    }
}
