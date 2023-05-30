<?php

namespace AsyncAws\CloudFormation\Enum;

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
