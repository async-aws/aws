<?php

namespace AsyncAws\DynamoDb\Enum;

final class TableClass
{
    public const STANDARD = 'STANDARD';
    public const STANDARD_INFREQUENT_ACCESS = 'STANDARD_INFREQUENT_ACCESS';

    public static function exists(string $value): bool
    {
        return isset([
            self::STANDARD => true,
            self::STANDARD_INFREQUENT_ACCESS => true,
        ][$value]);
    }
}
