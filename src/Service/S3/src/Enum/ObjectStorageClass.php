<?php

namespace AsyncAws\S3\Enum;

final class ObjectStorageClass
{
    public const AWS_BACKUP_LOW_COST_WARM = 'AWS_BACKUP_LOW_COST_WARM';
    public const AWS_BACKUP_WARM = 'AWS_BACKUP_WARM';
    public const DEEP_ARCHIVE = 'DEEP_ARCHIVE';
    public const EXPRESS_ONEZONE = 'EXPRESS_ONEZONE';
    public const FSX_ONTAP = 'FSX_ONTAP';
    public const FSX_OPENZFS = 'FSX_OPENZFS';
    public const GLACIER = 'GLACIER';
    public const GLACIER_IR = 'GLACIER_IR';
    public const INTELLIGENT_TIERING = 'INTELLIGENT_TIERING';
    public const ONEZONE_IA = 'ONEZONE_IA';
    public const OUTPOSTS = 'OUTPOSTS';
    public const REDUCED_REDUNDANCY = 'REDUCED_REDUNDANCY';
    public const SNOW = 'SNOW';
    public const STANDARD = 'STANDARD';
    public const STANDARD_IA = 'STANDARD_IA';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AWS_BACKUP_LOW_COST_WARM => true,
            self::AWS_BACKUP_WARM => true,
            self::DEEP_ARCHIVE => true,
            self::EXPRESS_ONEZONE => true,
            self::FSX_ONTAP => true,
            self::FSX_OPENZFS => true,
            self::GLACIER => true,
            self::GLACIER_IR => true,
            self::INTELLIGENT_TIERING => true,
            self::ONEZONE_IA => true,
            self::OUTPOSTS => true,
            self::REDUCED_REDUNDANCY => true,
            self::SNOW => true,
            self::STANDARD => true,
            self::STANDARD_IA => true,
        ][$value]);
    }
}
