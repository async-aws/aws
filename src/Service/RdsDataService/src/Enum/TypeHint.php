<?php

namespace AsyncAws\RdsDataService\Enum;

final class TypeHint
{
    public const DATE = 'DATE';
    public const DECIMAL = 'DECIMAL';
    public const JSON = 'JSON';
    public const TIME = 'TIME';
    public const TIMESTAMP = 'TIMESTAMP';
    public const UUID = 'UUID';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DATE => true,
            self::DECIMAL => true,
            self::JSON => true,
            self::TIME => true,
            self::TIMESTAMP => true,
            self::UUID => true,
        ][$value]);
    }
}
