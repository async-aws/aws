<?php

namespace AsyncAws\CodeDeploy\Enum;

/**
 * The action to take on instances in the original environment after a successful blue/green deployment.
 *
 * - `TERMINATE`: Instances are terminated after a specified wait time.
 * - `KEEP_ALIVE`: Instances are left running after they are deregistered from the load balancer and removed from the
 *   deployment group.
 */
final class InstanceAction
{
    public const KEEP_ALIVE = 'KEEP_ALIVE';
    public const TERMINATE = 'TERMINATE';

    public static function exists(string $value): bool
    {
        return isset([
            self::KEEP_ALIVE => true,
            self::TERMINATE => true,
        ][$value]);
    }
}
