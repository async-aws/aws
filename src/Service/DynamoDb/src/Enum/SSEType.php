<?php

namespace AsyncAws\DynamoDb\Enum;

class SSEType
{
    public const AES256 = 'AES256';
    public const KMS = 'KMS';

    public static function exists(string $value): bool
    {
        return isset([
            self::AES256 => true,
            self::KMS => true,
        ][$value]);
    }
}
