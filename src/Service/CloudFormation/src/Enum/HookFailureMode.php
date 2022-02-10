<?php

namespace AsyncAws\CloudFormation\Enum;

/**
 * Specify the hook failure mode for non-compliant resources in the followings ways.
 *
 * - `FAIL` Stops provisioning resources.
 * - `WARN` Allows provisioning to continue with a warning message.
 */
final class HookFailureMode
{
    public const FAIL = 'FAIL';
    public const WARN = 'WARN';

    public static function exists(string $value): bool
    {
        return isset([
            self::FAIL => true,
            self::WARN => true,
        ][$value]);
    }
}
