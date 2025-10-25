<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class GuardrailConverseImageFormat
{
    public const JPEG = 'jpeg';
    public const PNG = 'png';

    public static function exists(string $value): bool
    {
        return isset([
            self::JPEG => true,
            self::PNG => true,
        ][$value]);
    }
}
