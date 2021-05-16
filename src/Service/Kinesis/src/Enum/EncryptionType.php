<?php

namespace AsyncAws\Kinesis\Enum;

/**
 * The encryption type used. This value is one of the following:.
 *
 * - `KMS`
 * - `NONE`
 */
final class EncryptionType
{
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
