<?php

namespace AsyncAws\Athena\Enum;

/**
 * A database property that can be requested in the `AttributesToGet` parameter of `ListDatabases`.
 */
final class DatabaseAttributes
{
    public const NAME = 'NAME';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NAME => true,
        ][$value]);
    }
}
