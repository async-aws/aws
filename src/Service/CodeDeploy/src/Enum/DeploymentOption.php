<?php

namespace AsyncAws\CodeDeploy\Enum;

final class DeploymentOption
{
    public const WITHOUT_TRAFFIC_CONTROL = 'WITHOUT_TRAFFIC_CONTROL';
    public const WITH_TRAFFIC_CONTROL = 'WITH_TRAFFIC_CONTROL';

    public static function exists(string $value): bool
    {
        return isset([
            self::WITHOUT_TRAFFIC_CONTROL => true,
            self::WITH_TRAFFIC_CONTROL => true,
        ][$value]);
    }
}
