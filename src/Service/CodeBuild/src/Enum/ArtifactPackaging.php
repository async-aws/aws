<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The type of build output artifact to create:.
 *
 * - If `type` is set to `CODEPIPELINE`, CodePipeline ignores this value if specified. This is because CodePipeline
 *   manages its build output artifacts instead of CodeBuild.
 * - If `type` is set to `NO_ARTIFACTS`, this value is ignored if specified, because no build output is produced.
 * - If `type` is set to `S3`, valid values include:
 *
 *   - `NONE`: CodeBuild creates in the output bucket a folder that contains the build output. This is the default if
 *     `packaging` is not specified.
 *   - `ZIP`: CodeBuild creates in the output bucket a ZIP file that contains the build output.
 */
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
