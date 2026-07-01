<?php

namespace AsyncAws\CloudFormation\Enum;

final class DeploymentConfigMode
{
    public const EXPRESS = 'EXPRESS';
    public const STANDARD = 'STANDARD';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXPRESS => true,
            self::STANDARD => true,
        ][$value]);
    }
}
