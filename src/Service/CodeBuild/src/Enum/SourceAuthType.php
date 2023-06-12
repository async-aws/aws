<?php

namespace AsyncAws\CodeBuild\Enum;

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
