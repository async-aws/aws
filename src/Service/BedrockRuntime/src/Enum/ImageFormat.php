<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class ImageFormat
{
    public const GIF = 'gif';
    public const JPEG = 'jpeg';
    public const PNG = 'png';
    public const WEBP = 'webp';

    public static function exists(string $value): bool
    {
        return isset([
            self::GIF => true,
            self::JPEG => true,
            self::PNG => true,
            self::WEBP => true,
        ][$value]);
    }
}
