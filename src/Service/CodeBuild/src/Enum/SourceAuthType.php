<?php

namespace AsyncAws\CodeBuild\Enum;

final class SourceAuthType
{
    public const CODECONNECTIONS = 'CODECONNECTIONS';
    public const OAUTH = 'OAUTH';
    public const SECRETS_MANAGER = 'SECRETS_MANAGER';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CODECONNECTIONS => true,
            self::OAUTH => true,
            self::SECRETS_MANAGER => true,
        ][$value]);
    }
}
