<?php

namespace AsyncAws\ElastiCache\Enum;

final class IpDiscovery
{
    public const IPV_4 = 'ipv4';
    public const IPV_6 = 'ipv6';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::IPV_4 => true,
            self::IPV_6 => true,
        ][$value]);
    }
}
