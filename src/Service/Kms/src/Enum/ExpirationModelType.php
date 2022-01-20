<?php

namespace AsyncAws\Kms\Enum;

/**
 * Specifies whether the KMS key's key material expires. This value is present only when `Origin` is `EXTERNAL`,
 * otherwise this value is omitted.
 */
final class ExpirationModelType
{
    public const KEY_MATERIAL_DOES_NOT_EXPIRE = 'KEY_MATERIAL_DOES_NOT_EXPIRE';
    public const KEY_MATERIAL_EXPIRES = 'KEY_MATERIAL_EXPIRES';

    public static function exists(string $value): bool
    {
        return isset([
            self::KEY_MATERIAL_DOES_NOT_EXPIRE => true,
            self::KEY_MATERIAL_EXPIRES => true,
        ][$value]);
    }
}
