<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the S3 storage class to use for this output. To use your destination's default storage class: Keep the
 * default value, Not set. For more information about S3 storage classes, see
 * https://docs.aws.amazon.com/AmazonS3/latest/userguide/storage-class-intro.html.
 */
final class S3StorageClass
{
    public const DEEP_ARCHIVE = 'DEEP_ARCHIVE';
    public const GLACIER = 'GLACIER';
    public const INTELLIGENT_TIERING = 'INTELLIGENT_TIERING';
    public const ONEZONE_IA = 'ONEZONE_IA';
    public const REDUCED_REDUNDANCY = 'REDUCED_REDUNDANCY';
    public const STANDARD = 'STANDARD';
    public const STANDARD_IA = 'STANDARD_IA';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEEP_ARCHIVE => true,
            self::GLACIER => true,
            self::INTELLIGENT_TIERING => true,
            self::ONEZONE_IA => true,
            self::REDUCED_REDUNDANCY => true,
            self::STANDARD => true,
            self::STANDARD_IA => true,
        ][$value]);
    }
}
