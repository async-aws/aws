<?php

namespace AsyncAws\AppSync\Enum;

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
