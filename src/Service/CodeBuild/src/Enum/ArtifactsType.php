<?php

namespace AsyncAws\CodeBuild\Enum;

final class ArtifactsType
{
    public const CODEPIPELINE = 'CODEPIPELINE';
    public const NO_ARTIFACTS = 'NO_ARTIFACTS';
    public const S3 = 'S3';

    public static function exists(string $value): bool
    {
        return isset([
            self::CODEPIPELINE => true,
            self::NO_ARTIFACTS => true,
            self::S3 => true,
        ][$value]);
    }
}
