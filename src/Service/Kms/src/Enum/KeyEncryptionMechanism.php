<?php

namespace AsyncAws\Kms\Enum;

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
