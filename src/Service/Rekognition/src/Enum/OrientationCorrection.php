<?php

namespace AsyncAws\Rekognition\Enum;

/**
 * The orientation of the input image (counterclockwise direction). If your application displays the image, you can use
 * this value to correct the orientation. The bounding box coordinates returned in `CelebrityFaces` and
 * `UnrecognizedFaces` represent face locations before the image orientation is corrected.
 *
 * > If the input image is in .jpeg format, it might contain exchangeable image (Exif) metadata that includes the
 * > image's orientation. If so, and the Exif metadata for the input image populates the orientation field, the value of
 * > `OrientationCorrection` is null. The `CelebrityFaces` and `UnrecognizedFaces` bounding box coordinates represent
 * > face locations after Exif metadata is used to correct the image orientation. Images in .png format don't contain
 * > Exif metadata.
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
