<?php

namespace AsyncAws\Route53\Enum;

final class ResourceRecordSetFailover
{
    public const PRIMARY = 'PRIMARY';
    public const SECONDARY = 'SECONDARY';

    public static function exists(string $value): bool
    {
        return isset([
            self::PRIMARY => true,
            self::SECONDARY => true,
        ][$value]);
    }
}
