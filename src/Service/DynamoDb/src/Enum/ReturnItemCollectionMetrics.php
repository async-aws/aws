<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * Determines whether item collection metrics are returned. If set to `SIZE`, the response includes statistics about
 * item collections, if any, that were modified during the operation are returned in the response. If set to `NONE` (the
 * default), no statistics are returned.
 */
final class ReturnItemCollectionMetrics
{
    public const NONE = 'NONE';
    public const SIZE = 'SIZE';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::SIZE => true,
        ][$value]);
    }
}
