<?php

namespace AsyncAws\CodeDeploy\Enum;

final class AutoRollbackEvent
{
    public const DEPLOYMENT_FAILURE = 'DEPLOYMENT_FAILURE';
    public const DEPLOYMENT_STOP_ON_ALARM = 'DEPLOYMENT_STOP_ON_ALARM';
    public const DEPLOYMENT_STOP_ON_REQUEST = 'DEPLOYMENT_STOP_ON_REQUEST';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEPLOYMENT_FAILURE => true,
            self::DEPLOYMENT_STOP_ON_ALARM => true,
            self::DEPLOYMENT_STOP_ON_REQUEST => true,
        ][$value]);
    }
}
