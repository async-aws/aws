<?php

namespace AsyncAws\CodeBuild\Enum;

final class SourceAuthType
{
    public const CODECONNECTIONS = 'CODECONNECTIONS';
    public const OAUTH = 'OAUTH';

    public static function exists(string $value): bool
    {
        return isset([
            self::CODECONNECTIONS => true,
            self::OAUTH => true,
        ][$value]);
    }
}
