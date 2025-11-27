<?php

namespace AsyncAws\CodeDeploy\Enum;

final class ComputePlatform
{
    public const ECS = 'ECS';
    public const LAMBDA = 'Lambda';
    public const SERVER = 'Server';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ECS => true,
            self::LAMBDA => true,
            self::SERVER => true,
        ][$value]);
    }
}
