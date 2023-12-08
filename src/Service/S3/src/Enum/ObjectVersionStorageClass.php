<?php

namespace AsyncAws\S3\Enum;

final class ObjectVersionStorageClass
{
    public const STANDARD = 'STANDARD';

    public static function exists(string $value): bool
    {
        return isset([
            self::STANDARD => true,
        ][$value]);
    }
}
