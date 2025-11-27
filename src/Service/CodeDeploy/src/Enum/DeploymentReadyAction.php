<?php

namespace AsyncAws\CodeDeploy\Enum;

final class DeploymentReadyAction
{
    public const CONTINUE_DEPLOYMENT = 'CONTINUE_DEPLOYMENT';
    public const STOP_DEPLOYMENT = 'STOP_DEPLOYMENT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CONTINUE_DEPLOYMENT => true,
            self::STOP_DEPLOYMENT => true,
        ][$value]);
    }
}
