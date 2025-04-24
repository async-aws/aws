<?php

namespace AsyncAws\CodeBuild\Enum;

final class ComputeType
{
    public const ATTRIBUTE_BASED_COMPUTE = 'ATTRIBUTE_BASED_COMPUTE';
    public const BUILD_GENERAL1_2XLARGE = 'BUILD_GENERAL1_2XLARGE';
    public const BUILD_GENERAL1_LARGE = 'BUILD_GENERAL1_LARGE';
    public const BUILD_GENERAL1_MEDIUM = 'BUILD_GENERAL1_MEDIUM';
    public const BUILD_GENERAL1_SMALL = 'BUILD_GENERAL1_SMALL';
    public const BUILD_GENERAL1_XLARGE = 'BUILD_GENERAL1_XLARGE';
    public const BUILD_LAMBDA_10GB = 'BUILD_LAMBDA_10GB';
    public const BUILD_LAMBDA_1GB = 'BUILD_LAMBDA_1GB';
    public const BUILD_LAMBDA_2GB = 'BUILD_LAMBDA_2GB';
    public const BUILD_LAMBDA_4GB = 'BUILD_LAMBDA_4GB';
    public const BUILD_LAMBDA_8GB = 'BUILD_LAMBDA_8GB';
    public const CUSTOM_INSTANCE_TYPE = 'CUSTOM_INSTANCE_TYPE';

    public static function exists(string $value): bool
    {
        return isset([
            self::ATTRIBUTE_BASED_COMPUTE => true,
            self::BUILD_GENERAL1_2XLARGE => true,
            self::BUILD_GENERAL1_LARGE => true,
            self::BUILD_GENERAL1_MEDIUM => true,
            self::BUILD_GENERAL1_SMALL => true,
            self::BUILD_GENERAL1_XLARGE => true,
            self::BUILD_LAMBDA_10GB => true,
            self::BUILD_LAMBDA_1GB => true,
            self::BUILD_LAMBDA_2GB => true,
            self::BUILD_LAMBDA_4GB => true,
            self::BUILD_LAMBDA_8GB => true,
            self::CUSTOM_INSTANCE_TYPE => true,
        ][$value]);
    }
}
