<?php

namespace AsyncAws\ElastiCache\Enum;

final class TransitEncryptionMode
{
    public const PREFERRED = 'preferred';
    public const REQUIRED = 'required';

    public static function exists(string $value): bool
    {
        return isset([
            self::PREFERRED => true,
            self::REQUIRED => true,
        ][$value]);
    }
}
