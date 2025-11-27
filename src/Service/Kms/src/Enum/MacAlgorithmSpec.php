<?php

namespace AsyncAws\Kms\Enum;

final class MacAlgorithmSpec
{
    public const HMAC_SHA_224 = 'HMAC_SHA_224';
    public const HMAC_SHA_256 = 'HMAC_SHA_256';
    public const HMAC_SHA_384 = 'HMAC_SHA_384';
    public const HMAC_SHA_512 = 'HMAC_SHA_512';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HMAC_SHA_224 => true,
            self::HMAC_SHA_256 => true,
            self::HMAC_SHA_384 => true,
            self::HMAC_SHA_512 => true,
        ][$value]);
    }
}
