<?php

namespace AsyncAws\CodeBuild\Enum;

final class ArtifactNamespace
{
    public const BUILD_ID = 'BUILD_ID';
    public const NONE = 'NONE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BUILD_ID => true,
            self::NONE => true,
        ][$value]);
    }
}
