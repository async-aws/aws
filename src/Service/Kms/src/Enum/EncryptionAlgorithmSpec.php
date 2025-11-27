<?php

namespace AsyncAws\Kms\Enum;

final class EncryptionAlgorithmSpec
{
    public const RSAES_OAEP_SHA_1 = 'RSAES_OAEP_SHA_1';
    public const RSAES_OAEP_SHA_256 = 'RSAES_OAEP_SHA_256';
    public const SM2PKE = 'SM2PKE';
    public const SYMMETRIC_DEFAULT = 'SYMMETRIC_DEFAULT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::RSAES_OAEP_SHA_1 => true,
            self::RSAES_OAEP_SHA_256 => true,
            self::SM2PKE => true,
            self::SYMMETRIC_DEFAULT => true,
        ][$value]);
    }
}
