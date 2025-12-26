<?php

namespace AsyncAws\RdsDataService\Enum;

final class LongReturnType
{
    public const LONG = 'LONG';
    public const STRING = 'STRING';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::LONG => true,
            self::STRING => true,
        ][$value]);
    }
}
