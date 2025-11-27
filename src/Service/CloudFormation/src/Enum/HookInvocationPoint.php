<?php

namespace AsyncAws\CloudFormation\Enum;

final class HookInvocationPoint
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const PRE_PROVISION = 'PRE_PROVISION';

    public static function exists(string $value): bool
    {
        return isset([
            self::PRE_PROVISION => true,
        ][$value]);
    }
}
