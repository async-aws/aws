<?php

namespace AsyncAws\Kms\Enum;

final class KeyManagerType
{
    public const AWS = 'AWS';
    public const CUSTOMER = 'CUSTOMER';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AWS => true,
            self::CUSTOMER => true,
        ][$value]);
    }
}
