<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * The role that this key attribute will assume:.
 *
 * - `HASH` - partition key
 * - `RANGE` - sort key
 *
 * > The partition key of an item is also known as its *hash attribute*. The term "hash attribute" derives from
 * > DynamoDB's usage of an internal hash function to evenly distribute data items across partitions, based on their
 * > partition key values.
 * > The sort key of an item is also known as its *range attribute*. The term "range attribute" derives from the way
 * > DynamoDB stores items with the same partition key physically close together, in sorted order by the sort key value.
 */
final class KeyType
{
    public const HASH = 'HASH';
    public const RANGE = 'RANGE';

    public static function exists(string $value): bool
    {
        return isset([
            self::HASH => true,
            self::RANGE => true,
        ][$value]);
    }
}
