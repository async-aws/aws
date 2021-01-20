<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * Use `ReturnValues` if you want to get the item attributes as they appeared before they were deleted. For
 * `DeleteItem`, the valid values are:.
 *
 * - `NONE` - If `ReturnValues` is not specified, or if its value is `NONE`, then nothing is returned. (This setting is
 *   the default for `ReturnValues`.)
 * - `ALL_OLD` - The content of the old item is returned.
 *
 * > The `ReturnValues` parameter is used by several DynamoDB operations; however, `DeleteItem` does not recognize any
 * > values other than `NONE` or `ALL_OLD`.
 */
final class ReturnValue
{
    public const ALL_NEW = 'ALL_NEW';
    public const ALL_OLD = 'ALL_OLD';
    public const NONE = 'NONE';
    public const UPDATED_NEW = 'UPDATED_NEW';
    public const UPDATED_OLD = 'UPDATED_OLD';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL_NEW => true,
            self::ALL_OLD => true,
            self::NONE => true,
            self::UPDATED_NEW => true,
            self::UPDATED_OLD => true,
        ][$value]);
    }
}
