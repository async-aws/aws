<?php

namespace AsyncAws\CodeDeploy\Enum;

final class GreenFleetProvisioningAction
{
    public const COPY_AUTO_SCALING_GROUP = 'COPY_AUTO_SCALING_GROUP';
    public const DISCOVER_EXISTING = 'DISCOVER_EXISTING';

    public static function exists(string $value): bool
    {
        return isset([
            self::COPY_AUTO_SCALING_GROUP => true,
            self::DISCOVER_EXISTING => true,
        ][$value]);
    }
}
