<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify additional criteria for partitioning video outputs into separate AdaptationSets. These criteria are applied
 * on top of the grouping produced by DashManifestStyle.
 */
final class DashAdaptationSetGrouping
{
    public const CODEC = 'CODEC';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CODEC => true,
        ][$value]);
    }
}
