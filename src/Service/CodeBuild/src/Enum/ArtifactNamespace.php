<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * Along with `path` and `name`, the pattern that CodeBuild uses to determine the name and location to store the output
 * artifact:.
 *
 * - If `type` is set to `CODEPIPELINE`, CodePipeline ignores this value if specified. This is because CodePipeline
 *   manages its build output names instead of CodeBuild.
 * - If `type` is set to `NO_ARTIFACTS`, this value is ignored if specified, because no build output is produced.
 * - If `type` is set to `S3`, valid values include:
 *
 *   - `BUILD_ID`: Include the build ID in the location of the build output artifact.
 *   - `NONE`: Do not include the build ID. This is the default if `namespaceType` is not specified.
 *
 *
 * For example, if `path` is set to `MyArtifacts`, `namespaceType` is set to `BUILD_ID`, and `name` is set to
 * `MyArtifact.zip`, the output artifact is stored in `MyArtifacts/&lt;build-ID&gt;/MyArtifact.zip`.
 */
final class ArtifactNamespace
{
    public const BUILD_ID = 'BUILD_ID';
    public const NONE = 'NONE';

    public static function exists(string $value): bool
    {
        return isset([
            self::BUILD_ID => true,
            self::NONE => true,
        ][$value]);
    }
}
