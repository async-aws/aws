<?php

namespace AsyncAws\Lambda\Enum;

final class SchemaRegistryEventRecordFormat
{
    public const JSON = 'JSON';
    public const SOURCE = 'SOURCE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::JSON => true,
            self::SOURCE => true,
        ][$value]);
    }
}
