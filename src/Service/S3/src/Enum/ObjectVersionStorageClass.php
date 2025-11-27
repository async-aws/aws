<?php

namespace AsyncAws\S3\Enum;

final class ObjectVersionStorageClass
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const STANDARD = 'STANDARD';

    public static function exists(string $value): bool
    {
        return isset([
            self::STANDARD => true,
        ][$value]);
    }
}
