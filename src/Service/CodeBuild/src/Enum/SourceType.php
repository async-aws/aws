<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The type of repository that contains the source code to be built. Valid values include:.
 *
 * - `BITBUCKET`: The source code is in a Bitbucket repository.
 * - `CODECOMMIT`: The source code is in an CodeCommit repository.
 * - `CODEPIPELINE`: The source code settings are specified in the source action of a pipeline in CodePipeline.
 * - `GITHUB`: The source code is in a GitHub or GitHub Enterprise Cloud repository.
 * - `GITHUB_ENTERPRISE`: The source code is in a GitHub Enterprise Server repository.
 * - `NO_SOURCE`: The project does not have input source code.
 * - `S3`: The source code is in an Amazon S3 bucket.
 */
final class SourceType
{
    public const BITBUCKET = 'BITBUCKET';
    public const CODECOMMIT = 'CODECOMMIT';
    public const CODEPIPELINE = 'CODEPIPELINE';
    public const GITHUB = 'GITHUB';
    public const GITHUB_ENTERPRISE = 'GITHUB_ENTERPRISE';
    public const NO_SOURCE = 'NO_SOURCE';
    public const S3 = 'S3';

    public static function exists(string $value): bool
    {
        return isset([
            self::BITBUCKET => true,
            self::CODECOMMIT => true,
            self::CODEPIPELINE => true,
            self::GITHUB => true,
            self::GITHUB_ENTERPRISE => true,
            self::NO_SOURCE => true,
            self::S3 => true,
        ][$value]);
    }
}
