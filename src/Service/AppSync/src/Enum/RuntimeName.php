<?php

namespace AsyncAws\AppSync\Enum;

final class RuntimeName
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const APPSYNC_JS = 'APPSYNC_JS';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::APPSYNC_JS => true,
        ][$value]);
    }
}
