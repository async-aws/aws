<?php

namespace AsyncAws\Route53\Enum;

final class HostedZoneType
{
    public const PRIVATE_HOSTED_ZONE = 'PrivateHostedZone';

    public static function exists(string $value): bool
    {
        return isset([
            self::PRIVATE_HOSTED_ZONE => true,
        ][$value]);
    }
}
