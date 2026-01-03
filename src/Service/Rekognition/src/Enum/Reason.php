<?php

namespace AsyncAws\Rekognition\Enum;

final class Reason
{
    public const EXCEEDS_MAX_FACES = 'EXCEEDS_MAX_FACES';
    public const EXTREME_POSE = 'EXTREME_POSE';
    public const LOW_BRIGHTNESS = 'LOW_BRIGHTNESS';
    public const LOW_CONFIDENCE = 'LOW_CONFIDENCE';
    public const LOW_FACE_QUALITY = 'LOW_FACE_QUALITY';
    public const LOW_SHARPNESS = 'LOW_SHARPNESS';
    public const SMALL_BOUNDING_BOX = 'SMALL_BOUNDING_BOX';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXCEEDS_MAX_FACES => true,
            self::EXTREME_POSE => true,
            self::LOW_BRIGHTNESS => true,
            self::LOW_CONFIDENCE => true,
            self::LOW_FACE_QUALITY => true,
            self::LOW_SHARPNESS => true,
            self::SMALL_BOUNDING_BOX => true,
        ][$value]);
    }
}
