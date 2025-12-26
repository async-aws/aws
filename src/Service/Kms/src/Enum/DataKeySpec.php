<?php

namespace AsyncAws\Kms\Enum;

final class DataKeySpec
{
    public const AES_128 = 'AES_128';
    public const AES_256 = 'AES_256';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AES_128 => true,
            self::AES_256 => true,
        ][$value]);
    }
}
