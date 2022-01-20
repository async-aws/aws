<?php

namespace AsyncAws\Kms\Enum;

/**
 * Indicates whether the KMS key is a `PRIMARY` or `REPLICA` key.
 */
final class MultiRegionKeyType
{
    public const PRIMARY = 'PRIMARY';
    public const REPLICA = 'REPLICA';

    public static function exists(string $value): bool
    {
        return isset([
            self::PRIMARY => true,
            self::REPLICA => true,
        ][$value]);
    }
}
