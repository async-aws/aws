<?php

namespace AsyncAws\S3\Enum;

final class ChecksumAlgorithm
{
    public const CRC32 = 'CRC32';
    public const CRC32C = 'CRC32C';
    public const CRC64NVME = 'CRC64NVME';
    public const MD5 = 'MD5';
    public const SHA1 = 'SHA1';
    public const SHA256 = 'SHA256';
    public const SHA512 = 'SHA512';
    public const XXHASH128 = 'XXHASH128';
    public const XXHASH3 = 'XXHASH3';
    public const XXHASH64 = 'XXHASH64';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CRC32 => true,
            self::CRC32C => true,
            self::CRC64NVME => true,
            self::MD5 => true,
            self::SHA1 => true,
            self::SHA256 => true,
            self::SHA512 => true,
            self::XXHASH128 => true,
            self::XXHASH3 => true,
            self::XXHASH64 => true,
        ][$value]);
    }
}
