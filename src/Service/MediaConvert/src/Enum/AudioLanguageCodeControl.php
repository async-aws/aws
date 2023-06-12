<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify which source for language code takes precedence for this audio track. When you choose Follow input
 * (FOLLOW_INPUT), the service uses the language code from the input track if it's present. If there's no languge code
 * on the input track, the service uses the code that you specify in the setting Language code (languageCode or
 * customLanguageCode). When you choose Use configured (USE_CONFIGURED), the service uses the language code that you
 * specify.
 */
final class AudioLanguageCodeControl
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
