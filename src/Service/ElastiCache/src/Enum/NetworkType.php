<?php

namespace AsyncAws\ElastiCache\Enum;

/**
 * Must be either `ipv4` | `ipv6` | `dual_stack`. IPv6 is supported for workloads using Redis engine version 6.2 onward
 * or Memcached engine version 1.6.6 on all instances built on the Nitro system.
 *
 * @see http://aws.amazon.com/ec2/nitro/
 */
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
