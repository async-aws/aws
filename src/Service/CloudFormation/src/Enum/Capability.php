<?php

namespace AsyncAws\CloudFormation\Enum;

final class Capability
{
    public const CAPABILITY_AUTO_EXPAND = 'CAPABILITY_AUTO_EXPAND';
    public const CAPABILITY_IAM = 'CAPABILITY_IAM';
    public const CAPABILITY_NAMED_IAM = 'CAPABILITY_NAMED_IAM';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CAPABILITY_AUTO_EXPAND => true,
            self::CAPABILITY_IAM => true,
            self::CAPABILITY_NAMED_IAM => true,
        ][$value]);
    }
}
