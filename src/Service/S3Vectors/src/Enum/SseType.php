<?php

namespace AsyncAws\S3Vectors\Enum;

final class SseType
{
    public const AES256 = 'AES256';
    public const AWS_KMS = 'aws:kms';

    public static function exists(string $value): bool
    {
        return isset([
            self::AES256 => true,
            self::AWS_KMS => true,
        ][$value]);
    }
}
