<?php

namespace AsyncAws\CodeBuild\Enum;

final class BuildPhaseType
{
    public const BUILD = 'BUILD';
    public const COMPLETED = 'COMPLETED';
    public const DOWNLOAD_SOURCE = 'DOWNLOAD_SOURCE';
    public const FINALIZING = 'FINALIZING';
    public const INSTALL = 'INSTALL';
    public const POST_BUILD = 'POST_BUILD';
    public const PRE_BUILD = 'PRE_BUILD';
    public const PROVISIONING = 'PROVISIONING';
    public const QUEUED = 'QUEUED';
    public const SUBMITTED = 'SUBMITTED';
    public const UPLOAD_ARTIFACTS = 'UPLOAD_ARTIFACTS';

    public static function exists(string $value): bool
    {
        return isset([
            self::BUILD => true,
            self::COMPLETED => true,
            self::DOWNLOAD_SOURCE => true,
            self::FINALIZING => true,
            self::INSTALL => true,
            self::POST_BUILD => true,
            self::PRE_BUILD => true,
            self::PROVISIONING => true,
            self::QUEUED => true,
            self::SUBMITTED => true,
            self::UPLOAD_ARTIFACTS => true,
        ][$value]);
    }
}
