<?php

namespace AsyncAws\CodeDeploy\Enum;

/**
 * An Amazon EC2 Auto Scaling process that CodeDeploy can suspend during an in-place deployment. Valid values are
 * `Terminate`, `AZRebalance`, `InstanceRefresh`, and `ScheduledActions`.
 */
final class AutoScalingGroupProcess
{
    public const AZREBALANCE = 'AZRebalance';
    public const INSTANCE_REFRESH = 'InstanceRefresh';
    public const SCHEDULED_ACTIONS = 'ScheduledActions';
    public const TERMINATE = 'Terminate';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AZREBALANCE => true,
            self::INSTANCE_REFRESH => true,
            self::SCHEDULED_ACTIONS => true,
            self::TERMINATE => true,
        ][$value]);
    }
}
