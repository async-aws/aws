<?php

namespace AsyncAws\Kms\Enum;

final class ExpirationModelType
{
    public const KEY_MATERIAL_DOES_NOT_EXPIRE = 'KEY_MATERIAL_DOES_NOT_EXPIRE';
    public const KEY_MATERIAL_EXPIRES = 'KEY_MATERIAL_EXPIRES';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::KEY_MATERIAL_DOES_NOT_EXPIRE => true,
            self::KEY_MATERIAL_EXPIRES => true,
        ][$value]);
    }
}
