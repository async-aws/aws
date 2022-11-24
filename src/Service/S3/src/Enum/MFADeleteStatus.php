<?php

namespace AsyncAws\S3\Enum;

/**
 * Specifies whether MFA delete is enabled in the bucket versioning configuration. This element is only returned if the
 * bucket has been configured with MFA delete. If the bucket has never been so configured, this element is not returned.
 */
final class MFADeleteStatus
{
    public const DISABLED = 'Disabled';
    public const ENABLED = 'Enabled';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
