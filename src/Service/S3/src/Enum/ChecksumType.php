<?php

namespace AsyncAws\S3\Enum;

final class ChecksumType
{
    public const COMPOSITE = 'COMPOSITE';
    public const FULL_OBJECT = 'FULL_OBJECT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COMPOSITE => true,
            self::FULL_OBJECT => true,
        ][$value]);
    }
}
