<?php

namespace AsyncAws\DynamoDb\Enum;

final class SSEType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const AES256 = 'AES256';
    public const KMS = 'KMS';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AES256 => true,
            self::KMS => true,
        ][$value]);
    }
}
