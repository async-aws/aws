<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to FOLLOW_INPUT, encoder metadata will be sourced from the DD, DD+, or DolbyE decoder that supplied this
 * audio data. If audio was not supplied from one of these streams, then the static metadata settings will be used.
 */
final class Ac3MetadataControl
{
    public const FOLLOW_INPUT = 'FOLLOW_INPUT';
    public const USE_CONFIGURED = 'USE_CONFIGURED';

    public static function exists(string $value): bool
    {
        return isset([
            self::FOLLOW_INPUT => true,
            self::USE_CONFIGURED => true,
        ][$value]);
    }
}
