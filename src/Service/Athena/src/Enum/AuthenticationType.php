<?php

namespace AsyncAws\Athena\Enum;

final class AuthenticationType
{
    public const DIRECTORY_IDENTITY = 'DIRECTORY_IDENTITY';

    public static function exists(string $value): bool
    {
        return isset([
            self::DIRECTORY_IDENTITY => true,
        ][$value]);
    }
}
