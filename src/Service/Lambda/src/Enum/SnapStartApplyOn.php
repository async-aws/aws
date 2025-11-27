<?php

namespace AsyncAws\Lambda\Enum;

final class SnapStartApplyOn
{
    public const NONE = 'None';
    public const PUBLISHED_VERSIONS = 'PublishedVersions';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PUBLISHED_VERSIONS => true,
        ][$value]);
    }
}
