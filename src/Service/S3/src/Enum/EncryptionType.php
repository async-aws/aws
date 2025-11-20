<?php

namespace AsyncAws\S3\Enum;

final class EncryptionType
{
    public const NONE = 'NONE';
    public const SSE_C = 'SSE-C';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::SSE_C => true,
        ][$value]);
    }
}
