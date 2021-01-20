<?php

namespace AsyncAws\Rekognition\Enum;

/**
 * The value of `OrientationCorrection` is always null.
 * If the input image is in .jpeg format, it might contain exchangeable image file format (Exif) metadata that includes
 * the image's orientation. Amazon Rekognition uses this orientation information to perform image correction. The
 * bounding box coordinates are translated to represent object locations after the orientation information in the Exif
 * metadata is used to correct the image orientation. Images in .png format don't contain Exif metadata.
 * Amazon Rekognition doesn’t perform image correction for images in .png format and .jpeg images without orientation
 * information in the image Exif metadata. The bounding box coordinates aren't translated and represent the object
 * locations before the image is rotated.
 */
final class OrientationCorrection
{
    public const ROTATE_0 = 'ROTATE_0';
    public const ROTATE_180 = 'ROTATE_180';
    public const ROTATE_270 = 'ROTATE_270';
    public const ROTATE_90 = 'ROTATE_90';

    public static function exists(string $value): bool
    {
        return isset([
            self::ROTATE_0 => true,
            self::ROTATE_180 => true,
            self::ROTATE_270 => true,
            self::ROTATE_90 => true,
        ][$value]);
    }
}
