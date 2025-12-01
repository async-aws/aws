<?php

namespace AsyncAws\Kinesis\Enum;

final class EncryptionType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const KMS = 'KMS';
    public const NONE = 'NONE';

    public static function exists(string $value): bool
    {
        return isset([
            self::KMS => true,
            self::NONE => true,
        ][$value]);
    }
}
