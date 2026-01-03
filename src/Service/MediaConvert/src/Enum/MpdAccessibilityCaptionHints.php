<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Choose Include to have MediaConvert mark up your DASH manifest with `<Accessibility>` elements for embedded
 * 608 captions. This markup isn't generally required, but some video players require it to discover and play embedded
 * 608 captions. Keep the default value, Exclude, to leave these elements out. When you enable this setting, this is the
 * markup that MediaConvert includes in your manifest: `<Accessibility schemeIdUri="urn:scte:dash:cc:cea-608:2015"
 * value="CC1=eng"/>`.
 */
final class MpdAccessibilityCaptionHints
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
