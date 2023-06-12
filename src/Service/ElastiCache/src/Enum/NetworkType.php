<?php

namespace AsyncAws\ElastiCache\Enum;

final class NetworkType
{
    public const DUAL_STACK = 'dual_stack';
    public const IPV_4 = 'ipv4';
    public const IPV_6 = 'ipv6';

    public static function exists(string $value): bool
    {
        return isset([
            self::DUAL_STACK => true,
            self::IPV_4 => true,
            self::IPV_6 => true,
        ][$value]);
    }
}
