<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When you use DRM with CMAF outputs, choose whether the service writes the 128-bit encryption initialization vector in
 * the HLS and DASH manifests.
 */
final class CmafInitializationVectorInManifest
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';

    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
