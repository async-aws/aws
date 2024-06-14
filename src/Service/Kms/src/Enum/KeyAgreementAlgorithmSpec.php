<?php

namespace AsyncAws\Kms\Enum;

final class KeyAgreementAlgorithmSpec
{
    public const ECDH = 'ECDH';

    public static function exists(string $value): bool
    {
        return isset([
            self::ECDH => true,
        ][$value]);
    }
}
