<?php

namespace AsyncAws\AppSync\Enum;

/**
 * The `name` of the runtime to use. Currently, the only allowed value is `APPSYNC_JS`.
 */
final class RuntimeName
{
    public const APPSYNC_JS = 'APPSYNC_JS';

    public static function exists(string $value): bool
    {
        return isset([
            self::APPSYNC_JS => true,
        ][$value]);
    }
}
