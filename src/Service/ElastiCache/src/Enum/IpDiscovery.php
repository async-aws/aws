<?php

namespace AsyncAws\ElastiCache\Enum;

final class IpDiscovery
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const IPV_4 = 'ipv4';
    public const IPV_6 = 'ipv6';

    public static function exists(string $value): bool
    {
        return isset([
            self::IPV_4 => true,
            self::IPV_6 => true,
        ][$value]);
    }
}
