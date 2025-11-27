<?php

namespace AsyncAws\CodeDeploy\Enum;

final class DeploymentType
{
    public const BLUE_GREEN = 'BLUE_GREEN';
    public const IN_PLACE = 'IN_PLACE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BLUE_GREEN => true,
            self::IN_PLACE => true,
        ][$value]);
    }
}
