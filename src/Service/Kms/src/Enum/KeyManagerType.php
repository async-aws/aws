<?php

namespace AsyncAws\Kms\Enum;

final class KeyManagerType
{
    public const AWS = 'AWS';
    public const CUSTOMER = 'CUSTOMER';

    public static function exists(string $value): bool
    {
        return isset([
            self::AWS => true,
            self::CUSTOMER => true,
        ][$value]);
    }
}
