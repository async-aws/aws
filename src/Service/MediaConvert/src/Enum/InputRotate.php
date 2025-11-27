<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Rotate to specify how the service rotates your video. You can choose automatic rotation or specify a rotation.
 * You can specify a clockwise rotation of 0, 90, 180, or 270 degrees. If your input video container is .mov or .mp4 and
 * your input has rotation metadata, you can choose Automatic to have the service rotate your video according to the
 * rotation specified in the metadata. The rotation must be within one degree of 90, 180, or 270 degrees. If the
 * rotation metadata specifies any other rotation, the service will default to no rotation. By default, the service does
 * no rotation, even if your input video has rotation metadata. The service doesn't pass through rotation metadata.
 */
final class InputRotate
{
    public const AUTO = 'AUTO';
    public const DEGREES_180 = 'DEGREES_180';
    public const DEGREES_270 = 'DEGREES_270';
    public const DEGREES_90 = 'DEGREES_90';
    public const DEGREE_0 = 'DEGREE_0';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::DEGREES_180 => true,
            self::DEGREES_270 => true,
            self::DEGREES_90 => true,
            self::DEGREE_0 => true,
        ][$value]);
    }
}
