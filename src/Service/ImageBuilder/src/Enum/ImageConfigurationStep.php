<?php

namespace AsyncAws\ImageBuilder\Enum;

final class ImageConfigurationStep
{
    public const ASSOCIATE_LICENSES = 'ASSOCIATE_LICENSES';
    public const EXPORT_AMI = 'EXPORT_AMI';
    public const PUT_SSM_PARAMETERS = 'PUT_SSM_PARAMETERS';
    public const UPDATE_FAST_LAUNCH_CONFIGURATIONS = 'UPDATE_FAST_LAUNCH_CONFIGURATIONS';
    public const UPDATE_LAUNCH_TEMPLATES = 'UPDATE_LAUNCH_TEMPLATES';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ASSOCIATE_LICENSES => true,
            self::EXPORT_AMI => true,
            self::PUT_SSM_PARAMETERS => true,
            self::UPDATE_FAST_LAUNCH_CONFIGURATIONS => true,
            self::UPDATE_LAUNCH_TEMPLATES => true,
        ][$value]);
    }
}
