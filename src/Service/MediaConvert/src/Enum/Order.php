<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. When you request lists of resources, you can specify whether they are sorted in ASCENDING or DESCENDING
 * order. Default varies by resource.
 */
final class Order
{
    public const ASCENDING = 'ASCENDING';
    public const DESCENDING = 'DESCENDING';

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
