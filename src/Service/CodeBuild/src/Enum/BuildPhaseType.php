<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The name of the build phase. Valid values include:.
 *
 * - `BUILD`: Core build activities typically occur in this build phase.
 * - `COMPLETED`: The build has been completed.
 * - `DOWNLOAD_SOURCE`: Source code is being downloaded in this build phase.
 * - `FINALIZING`: The build process is completing in this build phase.
 * - `INSTALL`: Installation activities typically occur in this build phase.
 * - `POST_BUILD`: Post-build activities typically occur in this build phase.
 * - `PRE_BUILD`: Pre-build activities typically occur in this build phase.
 * - `PROVISIONING`: The build environment is being set up.
 * - `QUEUED`: The build has been submitted and is queued behind other submitted builds.
 * - `SUBMITTED`: The build has been submitted.
 * - `UPLOAD_ARTIFACTS`: Build output artifacts are being uploaded to the output location.
 */
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
