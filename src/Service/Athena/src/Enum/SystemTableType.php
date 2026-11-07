<?php

namespace AsyncAws\Athena\Enum;

final class SystemTableType
{
    public const QUERY_EXECUTIONS = 'QUERY_EXECUTIONS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::QUERY_EXECUTIONS => true,
        ][$value]);
    }
}
