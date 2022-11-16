<?php

namespace AsyncAws\Scheduler\Enum;

/**
 * Specifies the launch type on which your task is running. The launch type that you specify here must match one of the
 * launch type (compatibilities) of the target task. The `FARGATE` value is supported only in the Regions where Fargate
 * with Amazon ECS is supported. For more information, see AWS Fargate on Amazon ECS in the *Amazon ECS Developer
 * Guide*.
 *
 * @see https://docs.aws.amazon.com/AmazonECS/latest/developerguide/AWS_Fargate.html
 */
final class LaunchType
{
    public const EC2 = 'EC2';
    public const EXTERNAL = 'EXTERNAL';
    public const FARGATE = 'FARGATE';

    public static function exists(string $value): bool
    {
        return isset([
            self::EC2 => true,
            self::EXTERNAL => true,
            self::FARGATE => true,
        ][$value]);
    }
}
