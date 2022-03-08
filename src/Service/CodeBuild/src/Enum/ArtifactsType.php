<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The type of build output artifact. Valid values include:.
 *
 * - `CODEPIPELINE`: The build project has build output generated through CodePipeline.
 *
 *   > The `CODEPIPELINE` type is not supported for `secondaryArtifacts`.
 *
 * - `NO_ARTIFACTS`: The build project does not produce any build output.
 * - `S3`: The build project stores build output in Amazon S3.
 */
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
