<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The action to take on content advisory XDS packets. If you select PASSTHROUGH, packets will not be changed. If you
 * select STRIP, any packets will be removed in output captions.
 */
final class VchipAction
{
    public const PASSTHROUGH = 'PASSTHROUGH';
    public const STRIP = 'STRIP';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PASSTHROUGH => true,
            self::STRIP => true,
        ][$value]);
    }
}
