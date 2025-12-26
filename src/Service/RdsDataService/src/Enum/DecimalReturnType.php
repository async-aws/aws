<?php

namespace AsyncAws\RdsDataService\Enum;

final class DecimalReturnType
{
    public const DOUBLE_OR_LONG = 'DOUBLE_OR_LONG';
    public const STRING = 'STRING';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DOUBLE_OR_LONG => true,
            self::STRING => true,
        ][$value]);
    }
}
