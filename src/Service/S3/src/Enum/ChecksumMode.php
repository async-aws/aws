<?php

namespace AsyncAws\S3\Enum;

/**
 * To retrieve the checksum, this mode must be enabled.
 */
final class ChecksumMode
{
    public const ENABLED = 'ENABLED';

    public static function exists(string $value): bool
    {
        return isset([
            self::ENABLED => true,
        ][$value]);
    }
}
