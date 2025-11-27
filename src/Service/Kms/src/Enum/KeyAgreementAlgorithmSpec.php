<?php

namespace AsyncAws\Kms\Enum;

final class KeyAgreementAlgorithmSpec
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const ECDH = 'ECDH';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ECDH => true,
        ][$value]);
    }
}
