<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Elemental Inference feature.
 */
final class ElementalInferenceFeature
{
    public const SMART_CROP = 'SMART_CROP';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::SMART_CROP => true,
        ][$value]);
    }
}
