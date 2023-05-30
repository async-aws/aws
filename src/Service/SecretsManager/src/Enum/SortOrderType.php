<?php

namespace AsyncAws\SecretsManager\Enum;

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
