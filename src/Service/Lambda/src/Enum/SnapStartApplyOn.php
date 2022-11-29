<?php

namespace AsyncAws\Lambda\Enum;

/**
 * When set to `PublishedVersions`, Lambda creates a snapshot of the execution environment when you publish a function
 * version.
 */
final class SnapStartApplyOn
{
    public const NONE = 'None';
    public const PUBLISHED_VERSIONS = 'PublishedVersions';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PUBLISHED_VERSIONS => true,
        ][$value]);
    }
}
