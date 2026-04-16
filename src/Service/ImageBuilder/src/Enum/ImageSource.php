<?php

namespace AsyncAws\ImageBuilder\Enum;

final class ImageSource
{
    public const AMAZON_MANAGED = 'AMAZON_MANAGED';
    public const AWS_MARKETPLACE = 'AWS_MARKETPLACE';
    public const CUSTOM = 'CUSTOM';
    public const IMPORTED = 'IMPORTED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AMAZON_MANAGED => true,
            self::AWS_MARKETPLACE => true,
            self::CUSTOM => true,
            self::IMPORTED => true,
        ][$value]);
    }
}
