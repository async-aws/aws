<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The action to take on copy and redistribution control XDS packets. If you select PASSTHROUGH, packets will not be
 * changed. If you select STRIP, any packets will be removed in output captions.
 */
final class CopyProtectionAction
{
    public const PASSTHROUGH = 'PASSTHROUGH';
    public const STRIP = 'STRIP';

    public static function exists(string $value): bool
    {
        return isset([
            self::PASSTHROUGH => true,
            self::STRIP => true,
        ][$value]);
    }
}
