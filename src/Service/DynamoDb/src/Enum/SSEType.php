<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * Server-side encryption type. The only supported value is:.
 *
 * - `KMS` - Server-side encryption that uses Key Management Service. The key is stored in your account and is managed
 *   by KMS (KMS charges apply).
 */
final class SSEType
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
