<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * By default, the service terminates any unterminated captions at the end of each input. If you want the caption to
 * continue onto your next input, disable this setting.
 */
final class AncillaryTerminateCaptions
{
    public const DISABLED = 'DISABLED';
    public const END_OF_INPUT = 'END_OF_INPUT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::END_OF_INPUT => true,
        ][$value]);
    }
}
