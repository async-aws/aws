<?php

namespace AsyncAws\CodeDeploy\Enum;

/**
 * Information about how AWS CodeDeploy handles files that already exist in a deployment target location but weren't
 * part of the previous successful deployment.
 * The `fileExistsBehavior` parameter takes any of the following values:.
 *
 * - DISALLOW: The deployment fails. This is also the default behavior if no option is specified.
 * - OVERWRITE: The version of the file from the application revision currently being deployed replaces the version
 *   already on the instance.
 * - RETAIN: The version of the file already on the instance is kept and used as part of the new deployment.
 */
final class FileExistsBehavior
{
    public const DISALLOW = 'DISALLOW';
    public const OVERWRITE = 'OVERWRITE';
    public const RETAIN = 'RETAIN';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISALLOW => true,
            self::OVERWRITE => true,
            self::RETAIN => true,
        ][$value]);
    }
}
