<?php

namespace AsyncAws\Kinesis\Enum;

final class EncryptionType
{
    public const KMS = 'KMS';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::KMS => true,
            self::NONE => true,
        ][$value]);
    }
}
