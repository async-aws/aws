<?php

namespace AsyncAws\AppSync\Enum;

/**
 * Source type for the relational database.
 *
 * - **RDS_HTTP_ENDPOINT**: The relational database source type is an Amazon RDS HTTP endpoint.
 */
final class RelationalDatabaseSourceType
{
    public const RDS_HTTP_ENDPOINT = 'RDS_HTTP_ENDPOINT';

    public static function exists(string $value): bool
    {
        return isset([
            self::RDS_HTTP_ENDPOINT => true,
        ][$value]);
    }
}
