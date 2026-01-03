<?php

namespace AsyncAws\CloudFormation\Enum;

final class HookInvocationPoint
{
    public const PRE_PROVISION = 'PRE_PROVISION';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PRE_PROVISION => true,
        ][$value]);
    }
}
