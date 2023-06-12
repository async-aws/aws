<?php

namespace AsyncAws\CodeBuild\Enum;

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
