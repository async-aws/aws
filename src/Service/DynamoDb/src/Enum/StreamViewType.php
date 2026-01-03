<?php

namespace AsyncAws\DynamoDb\Enum;

final class StreamViewType
{
    public const KEYS_ONLY = 'KEYS_ONLY';
    public const NEW_AND_OLD_IMAGES = 'NEW_AND_OLD_IMAGES';
    public const NEW_IMAGE = 'NEW_IMAGE';
    public const OLD_IMAGE = 'OLD_IMAGE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
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
