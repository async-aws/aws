<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Indicates whether the output manifest should use floating point values for segment duration.
 */
final class CmafManifestDurationFormat
{
    public const FLOATING_POINT = 'FLOATING_POINT';
    public const INTEGER = 'INTEGER';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FLOATING_POINT => true,
            self::INTEGER => true,
        ][$value]);
    }
}
