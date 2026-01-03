<?php

namespace AsyncAws\RdsDataService\Enum;

final class RecordsFormatType
{
    public const JSON = 'JSON';
    public const NONE = 'NONE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::JSON => true,
            self::NONE => true,
        ][$value]);
    }
}
