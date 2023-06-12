<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether the encoder uses B-frames as reference frames for other pictures in the same GOP. Choose Allow
 * (ENABLED) to allow the encoder to use B-frames as reference frames. Choose Don't allow (DISABLED) to prevent the
 * encoder from using B-frames as reference frames.
 */
final class XavcGopBReference
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
