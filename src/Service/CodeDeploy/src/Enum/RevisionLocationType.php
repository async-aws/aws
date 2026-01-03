<?php

namespace AsyncAws\CodeDeploy\Enum;

final class RevisionLocationType
{
    public const APP_SPEC_CONTENT = 'AppSpecContent';
    public const GIT_HUB = 'GitHub';
    public const S3 = 'S3';
    public const STRING = 'String';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::APP_SPEC_CONTENT => true,
            self::GIT_HUB => true,
            self::S3 => true,
            self::STRING => true,
        ][$value]);
    }
}
