<?php

namespace AsyncAws\S3\Enum;

final class ObjectVersionStorageClass
{
    public const STANDARD = 'STANDARD';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::STANDARD => true,
        ][$value]);
    }
}
