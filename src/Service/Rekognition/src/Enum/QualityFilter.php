<?php

namespace AsyncAws\Rekognition\Enum;

/**
 * A filter that specifies a quality bar for how much filtering is done to identify faces. Filtered faces aren't
 * indexed. If you specify `AUTO`, Amazon Rekognition chooses the quality bar. If you specify `LOW`, `MEDIUM`, or
 * `HIGH`, filtering removes all faces that don’t meet the chosen quality bar. The default value is `AUTO`. The
 * quality bar is based on a variety of common use cases. Low-quality detections can occur for a number of reasons. Some
 * examples are an object that's misidentified as a face, a face that's too blurry, or a face with a pose that's too
 * extreme to use. If you specify `NONE`, no filtering is performed.
 * To use quality filtering, the collection you are using must be associated with version 3 of the face model or higher.
 */
final class QualityFilter
{
    public const AUTO = 'AUTO';
    public const HIGH = 'HIGH';
    public const LOW = 'LOW';
    public const MEDIUM = 'MEDIUM';
    public const NONE = 'NONE';

    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::HIGH => true,
            self::LOW => true,
            self::MEDIUM => true,
            self::NONE => true,
        ][$value]);
    }
}
