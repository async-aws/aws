<?php

namespace AsyncAws\Athena\Enum;

/**
 * The type of data catalog to create: `LAMBDA` for a federated catalog, `HIVE` for an external hive metastore, or
 * `GLUE` for an Glue Data Catalog.
 */
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
