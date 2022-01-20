<?php

namespace AsyncAws\Kms\Enum;

final class EncryptionAlgorithmSpec
{
    public const RSAES_OAEP_SHA_1 = 'RSAES_OAEP_SHA_1';
    public const RSAES_OAEP_SHA_256 = 'RSAES_OAEP_SHA_256';
    public const SYMMETRIC_DEFAULT = 'SYMMETRIC_DEFAULT';

    public static function exists(string $value): bool
    {
        return isset([
            self::RSAES_OAEP_SHA_1 => true,
            self::RSAES_OAEP_SHA_256 => true,
            self::SYMMETRIC_DEFAULT => true,
        ][$value]);
    }
}
