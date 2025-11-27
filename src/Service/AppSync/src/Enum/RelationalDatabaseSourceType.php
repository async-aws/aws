<?php

namespace AsyncAws\AppSync\Enum;

final class RelationalDatabaseSourceType
{
    public const RDS_HTTP_ENDPOINT = 'RDS_HTTP_ENDPOINT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::RDS_HTTP_ENDPOINT => true,
        ][$value]);
    }
}
