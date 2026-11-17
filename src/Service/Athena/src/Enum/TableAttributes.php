<?php

namespace AsyncAws\Athena\Enum;

/**
 * A table property that can be requested in the `AttributesToGet` parameter of `ListTableMetadata`.
 */
final class TableAttributes
{
    public const NAME = 'NAME';
    public const TABLE_TYPE = 'TABLE_TYPE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NAME => true,
            self::TABLE_TYPE => true,
        ][$value]);
    }
}
