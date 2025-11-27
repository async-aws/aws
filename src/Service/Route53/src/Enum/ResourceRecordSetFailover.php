<?php

namespace AsyncAws\Route53\Enum;

final class ResourceRecordSetFailover
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const PRIMARY = 'PRIMARY';
    public const SECONDARY = 'SECONDARY';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PRIMARY => true,
            self::SECONDARY => true,
        ][$value]);
    }
}
