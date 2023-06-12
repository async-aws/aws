<?php

namespace AsyncAws\Kms\Enum;

final class KeyUsageType
{
    public const ENCRYPT_DECRYPT = 'ENCRYPT_DECRYPT';
    public const GENERATE_VERIFY_MAC = 'GENERATE_VERIFY_MAC';
    public const SIGN_VERIFY = 'SIGN_VERIFY';

    public static function exists(string $value): bool
    {
        return isset([
            self::ENCRYPT_DECRYPT => true,
            self::GENERATE_VERIFY_MAC => true,
            self::SIGN_VERIFY => true,
        ][$value]);
    }
}
