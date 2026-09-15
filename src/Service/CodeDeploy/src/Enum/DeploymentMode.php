<?php

namespace AsyncAws\CodeDeploy\Enum;

final class DeploymentMode
{
    public const RESTART = 'RESTART';
    public const STANDARD = 'STANDARD';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::RESTART => true,
            self::STANDARD => true,
        ][$value]);
    }
}
