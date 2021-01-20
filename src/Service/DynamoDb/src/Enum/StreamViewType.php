<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * When an item in the table is modified, `StreamViewType` determines what information is written to the stream for this
 * table. Valid values for `StreamViewType` are:.
 *
 * - `KEYS_ONLY` - Only the key attributes of the modified item are written to the stream.
 * - `NEW_IMAGE` - The entire item, as it appears after it was modified, is written to the stream.
 * - `OLD_IMAGE` - The entire item, as it appeared before it was modified, is written to the stream.
 * - `NEW_AND_OLD_IMAGES` - Both the new and the old item images of the item are written to the stream.
 */
final class StreamViewType
{
    public const KEYS_ONLY = 'KEYS_ONLY';
    public const NEW_AND_OLD_IMAGES = 'NEW_AND_OLD_IMAGES';
    public const NEW_IMAGE = 'NEW_IMAGE';
    public const OLD_IMAGE = 'OLD_IMAGE';

    public static function exists(string $value): bool
    {
        return isset([
            self::KEYS_ONLY => true,
            self::NEW_AND_OLD_IMAGES => true,
            self::NEW_IMAGE => true,
            self::OLD_IMAGE => true,
        ][$value]);
    }
}
