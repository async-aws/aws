<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Provide the font script, using an ISO 15924 script code, if the LanguageCode is not sufficient for determining the
 * script type. Where LanguageCode or CustomLanguageCode is sufficient, use "AUTOMATIC" or leave unset.
 */
final class FontScript
{
    public const AUTOMATIC = 'AUTOMATIC';
    public const HANS = 'HANS';
    public const HANT = 'HANT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTOMATIC => true,
            self::HANS => true,
            self::HANT => true,
        ][$value]);
    }
}
