<?php

namespace AsyncAws\CodeBuild\Enum;

final class EnvironmentType
{
    public const ARM_CONTAINER = 'ARM_CONTAINER';
    public const ARM_EC2 = 'ARM_EC2';
    public const ARM_LAMBDA_CONTAINER = 'ARM_LAMBDA_CONTAINER';
    public const LINUX_CONTAINER = 'LINUX_CONTAINER';
    public const LINUX_EC2 = 'LINUX_EC2';
    public const LINUX_GPU_CONTAINER = 'LINUX_GPU_CONTAINER';
    public const LINUX_LAMBDA_CONTAINER = 'LINUX_LAMBDA_CONTAINER';
    public const MAC_ARM = 'MAC_ARM';
    public const WINDOWS_CONTAINER = 'WINDOWS_CONTAINER';
    public const WINDOWS_EC2 = 'WINDOWS_EC2';
    public const WINDOWS_SERVER_2019_CONTAINER = 'WINDOWS_SERVER_2019_CONTAINER';
    public const WINDOWS_SERVER_2022_CONTAINER = 'WINDOWS_SERVER_2022_CONTAINER';

    public static function exists(string $value): bool
    {
        return isset([
            self::ARM_CONTAINER => true,
            self::ARM_EC2 => true,
            self::ARM_LAMBDA_CONTAINER => true,
            self::LINUX_CONTAINER => true,
            self::LINUX_EC2 => true,
            self::LINUX_GPU_CONTAINER => true,
            self::LINUX_LAMBDA_CONTAINER => true,
            self::MAC_ARM => true,
            self::WINDOWS_CONTAINER => true,
            self::WINDOWS_EC2 => true,
            self::WINDOWS_SERVER_2019_CONTAINER => true,
            self::WINDOWS_SERVER_2022_CONTAINER => true,
        ][$value]);
    }
}
