<?php

namespace AsyncAws\S3Vectors\Enum;

/**
 * The backfill status of a text search analyzer.
 */
final class AnalyzerStatus
{
    public const ACTIVE = 'ACTIVE';
    public const BUILDING = 'BUILDING';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::BUILDING => true,
        ][$value]);
    }
}
