<?php

namespace AsyncAws\ElastiCache\Enum;

/**
 * The network type associated with the cluster, either `ipv4` | `ipv6`. IPv6 is supported for workloads using Redis
 * engine version 6.2 onward or Memcached engine version 1.6.6 on all instances built on the Nitro system.
 *
 * @see https://aws.amazon.com/ec2/nitro/
 */
final class IpDiscovery
{
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
