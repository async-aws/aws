<?php

namespace AsyncAws\SecretsManager\Enum;

/**
 * Secrets are listed by `CreatedDate`.
 */
final class SortOrderType
{
    public const ASC = 'asc';
    public const DESC = 'desc';

    public static function exists(string $value): bool
    {
        return isset([
            self::ASC => true,
            self::DESC => true,
        ][$value]);
    }
}
