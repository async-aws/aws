<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The type of credentials CodeBuild uses to pull images in your build. There are two valid values:.
 *
 * - `CODEBUILD` specifies that CodeBuild uses its own credentials. This requires that you modify your ECR repository
 *   policy to trust CodeBuild service principal.
 * - `SERVICE_ROLE` specifies that CodeBuild uses your build project's service role.
 *
 * When you use a cross-account or private registry image, you must use SERVICE_ROLE credentials. When you use an
 * CodeBuild curated image, you must use CODEBUILD credentials.
 */
final class ImagePullCredentialsType
{
    public const CODEBUILD = 'CODEBUILD';
    public const SERVICE_ROLE = 'SERVICE_ROLE';

    public static function exists(string $value): bool
    {
        return isset([
            self::CODEBUILD => true,
            self::SERVICE_ROLE => true,
        ][$value]);
    }
}
