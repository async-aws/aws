<?php

namespace AsyncAws\CodeCommit\Enum;

final class OrderEnum
{
    public const ASCENDING = 'ascending';
    public const DESCENDING = 'descending';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ASCENDING => true,
            self::DESCENDING => true,
        ][$value]);
    }
}
