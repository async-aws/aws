<?php

namespace AsyncAws\CodeDeploy\Enum;

/**
 * The destination platform type for the deployment (`Lambda`, `Server`, or `ECS`).
 */
final class ComputePlatform
{
    public const ECS = 'ECS';
    public const LAMBDA = 'Lambda';
    public const SERVER = 'Server';

    public static function exists(string $value): bool
    {
        return isset([
            self::ECS => true,
            self::LAMBDA => true,
            self::SERVER => true,
        ][$value]);
    }
}
