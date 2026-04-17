<?php

namespace AsyncAws\ImageBuilder\Enum;

final class ImageType
{
    public const AMI = 'AMI';
    public const DOCKER = 'DOCKER';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AMI => true,
            self::DOCKER => true,
        ][$value]);
    }
}
