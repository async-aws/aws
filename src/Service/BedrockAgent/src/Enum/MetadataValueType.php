<?php

namespace AsyncAws\BedrockAgent\Enum;

final class MetadataValueType
{
    public const BOOLEAN = 'BOOLEAN';
    public const NUMBER = 'NUMBER';
    public const STRING = 'STRING';
    public const STRING_LIST = 'STRING_LIST';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BOOLEAN => true,
            self::NUMBER => true,
            self::STRING => true,
            self::STRING_LIST => true,
        ][$value]);
    }
}
