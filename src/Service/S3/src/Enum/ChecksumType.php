<?php

namespace AsyncAws\S3\Enum;

final class ChecksumType
{
    public const COMPOSITE = 'COMPOSITE';
    public const FULL_OBJECT = 'FULL_OBJECT';

    public static function exists(string $value): bool
    {
        return isset([
            self::COMPOSITE => true,
            self::FULL_OBJECT => true,
        ][$value]);
    }
}
