<?php

namespace AsyncAws\ElastiCache\Enum;

final class TransitEncryptionMode
{
    public const PREFERRED = 'preferred';
    public const REQUIRED = 'required';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PREFERRED => true,
            self::REQUIRED => true,
        ][$value]);
    }
}
