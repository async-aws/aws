<?php

namespace AsyncAws\ImageBuilder\Enum;

final class SsmParameterDataType
{
    public const AWS_EC2_IMAGE = 'aws:ec2:image';
    public const TEXT = 'text';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AWS_EC2_IMAGE => true,
            self::TEXT => true,
        ][$value]);
    }
}
