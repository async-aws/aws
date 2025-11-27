<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether the encoder uses B-frames as reference frames for other pictures in the same GOP. Choose Allow to
 * allow the encoder to use B-frames as reference frames. Choose Don't allow to prevent the encoder from using B-frames
 * as reference frames.
 */
final class XavcGopBReference
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
