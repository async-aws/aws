<?php

namespace AsyncAws\Athena\Enum;

final class QueryResultType
{
    public const DATA_MANIFEST = 'DATA_MANIFEST';
    public const DATA_ROWS = 'DATA_ROWS';

    public static function exists(string $value): bool
    {
        return isset([
            self::DATA_MANIFEST => true,
            self::DATA_ROWS => true,
        ][$value]);
    }
}
