<?php

namespace AsyncAws\RdsDataService\Enum;

final class TypeHint
{
    public const DATE = 'DATE';
    public const DECIMAL = 'DECIMAL';
    public const TIME = 'TIME';
    public const TIMESTAMP = 'TIMESTAMP';

    public static function exists(string $value): bool
    {
        return isset([
            self::DATE => true,
            self::DECIMAL => true,
            self::TIME => true,
            self::TIMESTAMP => true,
        ][$value]);
    }
}
