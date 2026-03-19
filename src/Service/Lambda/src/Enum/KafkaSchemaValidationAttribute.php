<?php

namespace AsyncAws\Lambda\Enum;

final class KafkaSchemaValidationAttribute
{
    public const KEY = 'KEY';
    public const VALUE = 'VALUE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::KEY => true,
            self::VALUE => true,
        ][$value]);
    }
}
