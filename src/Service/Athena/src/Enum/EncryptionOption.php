<?php

namespace AsyncAws\Athena\Enum;

final class EncryptionOption
{
    public const CSE_KMS = 'CSE_KMS';
    public const SSE_KMS = 'SSE_KMS';
    public const SSE_S3 = 'SSE_S3';

    public static function exists(string $value): bool
    {
        return isset([
            self::CSE_KMS => true,
            self::SSE_KMS => true,
            self::SSE_S3 => true,
        ][$value]);
    }
}
