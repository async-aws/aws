<?php

namespace AsyncAws\Athena\Enum;

final class AuthenticationType
{
    public const DIRECTORY_IDENTITY = 'DIRECTORY_IDENTITY';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DIRECTORY_IDENTITY => true,
        ][$value]);
    }
}
