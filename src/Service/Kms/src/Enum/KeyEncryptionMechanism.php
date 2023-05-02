<?php

namespace AsyncAws\Kms\Enum;

/**
 * The encryption algorithm that KMS should use with the public key for an Amazon Web Services Nitro Enclave to encrypt
 * plaintext values for the response. The only valid value is `RSAES_OAEP_SHA_256`.
 */
final class KeyEncryptionMechanism
{
    public const RSAES_OAEP_SHA_256 = 'RSAES_OAEP_SHA_256';

    public static function exists(string $value): bool
    {
        return isset([
            self::RSAES_OAEP_SHA_256 => true,
        ][$value]);
    }
}
