<?php

namespace AsyncAws\DynamoDb\Enum;

final class SearchSchemaElementType
{
    public const HASH = 'HASH';
    public const INLINE_FILTER = 'INLINE_FILTER';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HASH => true,
            self::INLINE_FILTER => true,
        ][$value]);
    }
}
