<?php

namespace AsyncAws\Ses\Enum;

final class DkimSigningKeyLength
{
    public const RSA_1024_BIT = 'RSA_1024_BIT';
    public const RSA_2048_BIT = 'RSA_2048_BIT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::RSA_1024_BIT => true,
            self::RSA_2048_BIT => true,
        ][$value]);
    }
}
