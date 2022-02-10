<?php

namespace AsyncAws\CloudFormation\Enum;

/**
 * Invocation points are points in provisioning logic where hooks are initiated.
 */
final class HookInvocationPoint
{
    public const PRE_PROVISION = 'PRE_PROVISION';

    public static function exists(string $value): bool
    {
        return isset([
            self::PRE_PROVISION => true,
        ][$value]);
    }
}
