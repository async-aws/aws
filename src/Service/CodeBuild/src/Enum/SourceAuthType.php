<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * > This data type is deprecated and is no longer accurate or used.
 *
 * The authorization type to use. The only valid value is `OAUTH`, which represents the OAuth authorization type.
 */
final class SourceAuthType
{
    public const OAUTH = 'OAUTH';

    public static function exists(string $value): bool
    {
        return isset([
            self::OAUTH => true,
        ][$value]);
    }
}
