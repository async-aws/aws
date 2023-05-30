<?php

namespace AsyncAws\Athena\Enum;

final class DataCatalogType
{
    public const GLUE = 'GLUE';
    public const HIVE = 'HIVE';
    public const LAMBDA = 'LAMBDA';

    public static function exists(string $value): bool
    {
        return isset([
            self::GLUE => true,
            self::HIVE => true,
            self::LAMBDA => true,
        ][$value]);
    }
}
