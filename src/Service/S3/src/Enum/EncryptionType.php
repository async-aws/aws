<?php

namespace AsyncAws\S3\Enum;

final class EncryptionType
{
    public const NONE = 'NONE';
    public const SSE_C = 'SSE-C';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::SSE_C => true,
        ][$value]);
    }
}
