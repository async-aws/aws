<?php

namespace AsyncAws\CodeBuild\Enum;

final class BucketOwnerAccess
{
    public const FULL = 'FULL';
    public const NONE = 'NONE';
    public const READ_ONLY = 'READ_ONLY';

    public static function exists(string $value): bool
    {
        return isset([
            self::FULL => true,
            self::NONE => true,
            self::READ_ONLY => true,
        ][$value]);
    }
}
