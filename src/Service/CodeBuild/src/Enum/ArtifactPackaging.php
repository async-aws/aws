<?php

namespace AsyncAws\CodeBuild\Enum;

final class ArtifactPackaging
{
    public const NONE = 'NONE';
    public const ZIP = 'ZIP';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::ZIP => true,
        ][$value]);
    }
}
