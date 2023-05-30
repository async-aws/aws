<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Indicates whether the output manifest should use floating point values for segment duration.
 */
final class CmafManifestDurationFormat
{
    public const FLOATING_POINT = 'FLOATING_POINT';
    public const INTEGER = 'INTEGER';

    public static function exists(string $value): bool
    {
        return isset([
            self::FLOATING_POINT => true,
            self::INTEGER => true,
        ][$value]);
    }
}
