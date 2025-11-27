<?php

namespace AsyncAws\Kms\Enum;

final class MultiRegionKeyType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const PRIMARY = 'PRIMARY';
    public const REPLICA = 'REPLICA';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PRIMARY => true,
            self::REPLICA => true,
        ][$value]);
    }
}
