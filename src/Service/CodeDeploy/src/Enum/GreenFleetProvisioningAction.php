<?php

namespace AsyncAws\CodeDeploy\Enum;

/**
 * The method used to add instances to a replacement environment.
 *
 * - `DISCOVER_EXISTING`: Use instances that already exist or will be created manually.
 * - `COPY_AUTO_SCALING_GROUP`: Use settings from a specified Auto Scaling group to define and create instances in a new
 *   Auto Scaling group.
 */
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
