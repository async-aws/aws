<?php

namespace AsyncAws\S3\Enum;

final class OptionalObjectAttributes
{
    public const RESTORE_STATUS = 'RestoreStatus';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::RESTORE_STATUS => true,
        ][$value]);
    }
}
