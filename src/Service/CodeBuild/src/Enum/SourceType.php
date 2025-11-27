<?php

namespace AsyncAws\CodeBuild\Enum;

final class SourceType
{
    public const BITBUCKET = 'BITBUCKET';
    public const CODECOMMIT = 'CODECOMMIT';
    public const CODEPIPELINE = 'CODEPIPELINE';
    public const GITHUB = 'GITHUB';
    public const GITHUB_ENTERPRISE = 'GITHUB_ENTERPRISE';
    public const GITLAB = 'GITLAB';
    public const GITLAB_SELF_MANAGED = 'GITLAB_SELF_MANAGED';
    public const NO_SOURCE = 'NO_SOURCE';
    public const S3 = 'S3';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BITBUCKET => true,
            self::CODECOMMIT => true,
            self::CODEPIPELINE => true,
            self::GITHUB => true,
            self::GITHUB_ENTERPRISE => true,
            self::GITLAB => true,
            self::GITLAB_SELF_MANAGED => true,
            self::NO_SOURCE => true,
            self::S3 => true,
        ][$value]);
    }
}
