<?php

namespace AsyncAws\CodeDeploy\Enum;

final class DeploymentReadyAction
{
    public const CONTINUE_DEPLOYMENT = 'CONTINUE_DEPLOYMENT';
    public const STOP_DEPLOYMENT = 'STOP_DEPLOYMENT';

    public static function exists(string $value): bool
    {
        return isset([
            self::CONTINUE_DEPLOYMENT => true,
            self::STOP_DEPLOYMENT => true,
        ][$value]);
    }
}
